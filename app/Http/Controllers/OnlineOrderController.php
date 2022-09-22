<?php

namespace App\Http\Controllers;

use App\Models\{
    AddOnItem,
    Meal,
    MealPlan,
    MealPlanCart,
    MealPlanCartItem,
    MealPlanOrder,
    NewsletterSignup,
    PaymentMethod,
    PaymentToken,
    PromoCode,
    StoreLocation,
    User,
};
use App\Helpers\PaymentManager;
use App\Mail\CustomOrderNotification;
use App\Mail\OrderComplete;
use App\ViewModels\MealPlanCartViewModel;
use Carbon\Carbon;
use Corcel\Model\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{App, Log, URL, Validator};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OnlineOrderController extends SiteController
{
    private string $cookieIdKey = 'cookie_id';

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $defaultStoreCode = intval($request->get('store-code'));
        return view('site.order', compact('defaultStoreCode'));
    }

  

    public function thankyou()
    {
        return view(
            'site.thank-you-order',
        );
    }

    public function mealPlanMenu(Request $request)
    {
        $formData = new class {};

        if ($request->input('edit', false)) {
            $formData = $this->getCurrentShoppingCartFormData();
            $this->getCurrentShoppingCart()
                ->items()
                ->delete();
            $this->getCurrentShoppingCart()->delete();
        }

        $this->setCookieId();
        $mealPlan = MealPlan::with('items')
            ->current()
            ->first();

        $nextMealPlan = MealPlan::with('items')
            ->upcoming()
            ->first();

        $adminOnlyOrdering = env('ADMIN_ONLY_ORDER');
        $isAdmin = Auth::check() && Auth::user()->isAdmin();

        if ($adminOnlyOrdering && !$isAdmin) {
            $mealPlan = null;
        }

        $storeLocations = StoreLocation::orderBy('state')
            ->orderBy('city')
            ->orderBy('location')
            ->get();

        $addOnItems = Meal::getAddOnItems();

        return view(
            'site.meal-plan-menu',
            compact('mealPlan', 'formData', 'storeLocations', 'addOnItems', 'nextMealPlan'),
        );
    }

    public function addToCart(Request $request)
    {
        $firstMealPlanItem = collect($request->get('quantity'))->first(function ($value, $key) {
            return intval($value) > 0;
        });

        if ($firstMealPlanItem === null) {
            return redirect()
                ->route('site.meal-plan-menu')
                ->withErrors(['no_meal_ordered' => 'true']);
        }

        $cookieId = $this->getCookieId();

        $mealPlan = MealPlan::current()->firstOrFail();

        if (
            $request->has('meal_plan_id') &&
            $mealPlan->id !== (int) $request->get('meal_plan_id')
        ) {
            return redirect()
                ->route('site.meal-plan-menu')
                ->withErrors(['meal_plan_expired' => 'true']);
        }

        $storeLocation = StoreLocation::where(
            'code',
            '=',
            $request->get('store_location'),
        )->firstOrFail();

        $specialRequestOptions = $request->only(MealPlan::$requestOptions);
        $specialRequests = collect($specialRequestOptions)
            ->map(function ($value) {
                return $value === 'on';
            })
            ->toArray();

        $cartProperties = [
            'cookie_id' => $cookieId,
            'meal_plan_id' => $mealPlan->id,
            'store_location_id' => $storeLocation->id,
            'special_requests' => json_encode($specialRequests),
        ];

        if ($request->pick_up_options === 'satellite') {
            $cartProperties['satellite_location_id'] = $request->satellite;
        } elseif ($request->pick_up_options === 'delivery') {
            $cartProperties['delivery'] = true;
            $addressFields = ['address', 'apt', 'city', 'state', 'zip', 'phone'];
            $cartProperties['delivery_address'] = json_encode($request->only($addressFields));
        }

        $cart = MealPlanCart::create($cartProperties);

        if (Auth::check()) {
            $cart
                ->user()
                ->associate(Auth::user()->id)
                ->save();
        }

        $order = collect($request->get('quantity'));
        $totalItemsOrdered = $order->values()->sum();

        // store the meals ordered
        $orderedItems = collect($request->get('quantity'))->map(function ($quantity, $mealId) use (
            $totalItemsOrdered,
            $specialRequests,
        ) {
            if ($quantity > 1000) {
                $quantity = 10000;
            }

            $meal = Meal::findOrFail($mealId);
            $pricePerItem = MealPlan::pricePerMeal($totalItemsOrdered, $specialRequests, $meal);

            return new MealPlanCartItem([
                'meal_id' => $mealId,
                'meal_name' => $meal->name,
                'group_name' => $meal->group_name,
                'is_add_on_item' => $meal->is_add_on_item,
                'quantity' => abs($quantity),
                'cost' => $pricePerItem,
            ]);
        })->filter->quantity;

        $cart->items()->saveMany($orderedItems);

        // store the add on items ordered
        $orderedAddOnItems = collect($request->get('addOnQuantity'))->map(function (
            $quantity,
            $mealId,
        ) {
            if ($quantity > 1000) {
                $quantity = 10000;
            }

            $meal = Meal::findOrFail($mealId);

            return new MealPlanCartItem([
                'meal_id' => $mealId,
                'meal_name' => $meal->display_name,
                'group_name' => $meal->group_name,
                'is_add_on_item' => $meal->is_add_on_item,
                'quantity' => abs($quantity),
                'cost' => $meal->price_override,
            ]);
        })->filter->quantity;

        $cart->items()->saveMany($orderedAddOnItems);

        if ($request->get('promo_code')) {
            $this->attachPromoCode($request->get('promo_code'), $storeLocation->id);
        }

        return redirect(route('site.shopping-cart'))->with('addedToCart', [
            'items' => $orderedItems->values(),
            'subtotal' => $cart->sub_total,
        ]);
    }

    public function addCustomMealsToCart(Request $request)
    {
        $cookieId = $this->getCookieId();
        $mealPlan = MealPlan::current()->first();

        if ($mealPlan === null) {
            $mealPlan = MealPlan::orderBy('expires_on', 'desc')->first();
        }

        $storeLocation = StoreLocation::where(
            'code',
            '=',
            $request->get('store_location'),
        )->firstOrFail();

        $cart = MealPlanCart::create([
            'cookie_id' => $cookieId,
            'meal_plan_id' => $mealPlan->id,
            'store_location_id' => $storeLocation->id,
            'user_id' => Auth::check() ? Auth::user()->id : null,
            'is_custom' => true,
        ]);

        $orderedItems = collect(json_decode($request->meals))->map(function ($meal) {
            return new MealPlanCartItem([
                'meal_id' => Meal::CUSTOM_MEAL_ID,
                'meal_name' => 'Custom Meal',
                'quantity' => $meal->qty,
                'cost' => Meal::calcCustomMealCost($meal),
                'meta_data' => json_encode($meal),
            ]);
        });

        $cart->items()->saveMany($orderedItems);

        return redirect(route('site.shopping-cart'));
    }

    public function showShoppingCart(Request $request)
    {
        $cart = $this->getCurrentShoppingCart();

        if (Auth::check() && $cart->user()->exists() === false) {
            $cart
                ->user()
                ->associate(Auth::user())
                ->save();
        }

        $request->session()->put('url.intended', Auth::check() ? '' : '/order/cart');
        $viewModel = new MealPlanCartViewModel($cart);

        return view('site.meal-plan-cart', $viewModel);
    }

    public function addCouponToCart(Request $request)
    {
        $this->attachPromoCode($request->get('promo-code'), $request->get('store-location'));
        return redirect(route('site.shopping-cart'));
    }

    public function removeCouponFromCart(Request $request)
    {
        MealPlanCart::where('cookie_id', '=', $this->getCookieId())->update([
            'promo_code_id' => null,
        ]);

        return redirect(route('site.shopping-cart'));
    }

    public function save(Request $request)
    {
        // $request->back() doesn't work with proxied URL routes, so using
        // manual validation and redirects here.
        $validator = Validator::make($request->all(), $this->orderRules());

        if ($validator->fails()) {
            return redirect(route('site.shopping-cart'))
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();

        if ($input['card_token'] && !Auth::check()) {
            return redirect(route('site.shopping-cart'))
                ->withInput()
                ->withErrors([
                    'charge_failed' =>
                        'Could not use stored card because you are no longer logged in.',
                ]);
        }

        // verify the meal plan is currently active
        $mealPlan = MealPlan::current()->first();
        if ($mealPlan === null) {
            return redirect()
                ->route('site.meal-plan-menu')
                ->withErrors(['meal_plan_expired' => 'true']);
        }

        $cart = $this->getCurrentShoppingCart();

        // default values
        $paymentResponse = ['success' => true];
        $transactionId = null;
        $transactionStatus = MealPlanOrder::STATUS_COMPLETED;
        $transactionDetails = null;
        $cardBrand = null;
        $cardLastFour = null;
        $cardExpMonth = null;
        $cardExpYear = null;

        $tipAmount = 0;
        if (isset($input['tip_amount'])) {
            if (is_numeric($input['tip_amount'])) {
                $tipAmount = $input['tip_amount'];
            } else {
                $moneyFormatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
                $tipAmount = $moneyFormatter->parseCurrency($input['tip_amount'], $cur);
            }
        }
        $tipAmount = abs($tipAmount);

        $orderTotal = round($cart->order_total + $tipAmount, 2);

        // if we are taking an online payment, validate the card
        if ($input['payment_type'] === 'online') {
            // if we are tokenizing a new card
            if ($input['client_token']) {
                $card = PaymentToken::where('client_token', '=', $input['client_token'])->first();

                // we are using a stored card
            } elseif ($input['card_token'] && Auth::check()) {
                $card = PaymentMethod::where('user_id', '=', Auth::user()->id)
                    ->where('card_token', '=', $input['card_token'])
                    ->firstOrFail();
            }

            $payment = new PaymentManager($cart->storeLocation->gateway_merchant_token);
            $paymentResponse = $payment
                ->setName($card->card_name)
                ->setAmount($orderTotal)
                ->setCardExpiry($card->card_exp_month, $card->card_exp_year)
                ->setCardType($card->card_brand)
                ->setPaymentToken($card->card_token)
                ->purchase();

            $cardBrand = $card->card_brand;
            $cardLastFour = $card->card_last_4;
            $cardExpMonth = $card->card_exp_month;
            $cardExpYear = $card->card_exp_year;

            if ($paymentResponse['success'] === true) {
                $transactionId = $paymentResponse['transaction_id'];
                if (isset($input['save_card']) && Auth::check()) {
                    // in this instance the "card" is the payment token
                    $card->storeForUser(Auth::user()->id);
                }
            } else {
                $responseCode = intval($paymentResponse['error_code']);
                $transactionStatus =
                    $responseCode === 500
                        ? MealPlanOrder::STATUS_ERROR
                        : MealPlanOrder::STATUS_DECLINED;
            }

            $transactionDetails = json_encode($paymentResponse);
        }

        $isNewCustomer = MealPlanOrder::where('email', '=', $input['email'])->exists() === false;

        $order = new MealPlanOrder($input);
        $order->tip_amount = $tipAmount;
        $order->subtotal = $cart->sub_total;
        $order->tax = $cart->total_tax;
        $order->total = $orderTotal;
        $order->promo_amount = $cart->discount_amount;
        $order->promo_code = $cart->promoCode !== null ? $cart->promoCode->code : null;
        $order->user_id = $cart->user_id;
        $order->order_type = 'meal-plan';
        $order->store_location_id = $cart->store_location_id;
        $order->satellite_location_id = $cart->satellite_location_id;
        $order->satellite_fee = $cart->satellite_fee;
        $order->transaction_id = $transactionId;
        $order->transaction_status = $transactionStatus;
        $order->transaction_details = $transactionDetails;
        $order->card_last_4 = $cardLastFour;
        $order->card_exp_month = $cardExpMonth;
        $order->card_exp_year = $cardExpYear;
        $order->card_brand = $cardBrand;

        if ($cart->delivery) {
            $order->delivery_fee = $cart->delivery_fee;
            $order->delivery = true;

            $deliveryAddress = json_decode($cart->delivery_address);
            $order->address = $deliveryAddress->address;
            $order->address2 = $deliveryAddress->apt;
            $order->city = $deliveryAddress->city;
            $order->state = $deliveryAddress->state;
            $order->zip = $deliveryAddress->zip;
            $order->phone = $deliveryAddress->phone;
        }

        if ($transactionStatus === MealPlanOrder::STATUS_COMPLETED) {
            $order->meal_plan_cart_id = $cart->id;
            $cart->order_placed_on = Carbon::now();
            $cart->save();
        }

        $order->save();

        if ($isNewCustomer) {
            $order->attachTag('new_customer');
        }

        // tag the order if the user has signed up for the WCL newsletter
        if (
            NewsletterSignup::whereAudienceId('dffed89dd4')
                ->where('email', '=', $input['email'])
                ->exists()
        ) {
            $order->attachTag('wcl_signup');
        }

        if ($paymentResponse['success'] !== true) {
            return redirect(route('site.shopping-cart'))
                ->withInput()
                ->withErrors([
                    'charge_failed' => $paymentResponse['error_description'] ?? 'Charge declined',
                ]);
        }

        $request->session()->put('url.intended', '');
       

        if ($cart->is_custom) {
            Mail::to($order->store->email)->queue(new CustomOrderNotification($order));
        }
        // else{
        //     Mail::to($order->email)->queue(new OrderComplete($order));
        // }
        
        $signedUrl = URL::signedRoute(
            'site.order-complete',
            ['order' => $order],
            now()->addDays(7),
            false,
        );

        return redirect($signedUrl);
    }

    public function orderComplete(MealPlanOrder $order, Request $request)
    {
        if (!URL::hasValidRelativeSignature($request)) {
            abort(401);
        }

        $enableConversionScripts =
            App::environment('production') || env('WP_CONVERSION_TRACK_DEV', false);

        $purchaseScript = '';
        if ($enableConversionScripts && $order->total) {
            $conversionScripts = Option::get('cleaneatz_conversion_tracking');
            if (isset($conversionScripts['purchase_code'])) {
                $purchaseScript = $conversionScripts['purchase_code'];
                $purchaseScript = str_replace('${price}', (string) $order->total, $purchaseScript);
                $purchaseScript = str_replace(
                    '${transactionId}',
                    $order->transaction_id,
                    $purchaseScript,
                );
            }
        }

        $promptToRegister = false;

        if (Auth::guest() && !$order->user_id) {
            $existingUser = User::byUsername($order->email)->first();

            $promptToRegister = !$existingUser;
        }

        return view(
            'site.online-order.order-complete.index',
            compact('order', 'promptToRegister', 'purchaseScript'),
        );
    }

    public function login(Request $request)
    {
        // Remove the admin subdomain from the intended URL for the redirect back.
        $request->session()->put('url.intended', route('site.shopping-cart'));

        return redirect(route('login'));
    }

    public function customMenu()
    {
        $this->setCookieId();

        $options = Meal::getCustomOptions();

        return view('site.custom-menu', [
            'proteins' => $options['protein'],
            'proteinPortions' => $options['protein_portion'],
            'carbs' => $options['carbohydrate'],
            'carbPortions' => $options['carbohydrate_portion'],
            'vegetables' => $options['vegetables'],
            'vegetables_2' => $options['vegetables_2'],
            'vegetables_3' => $options['vegetables_3'],
            'sauces' => $options['sauce'],
            'basePrice' => Meal::CUSTOM_MEAL_BASE_PRICE,
        ]);
    }

    private function orderRules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'tip_amount' => 'nullable|numeric',
            'payment_type' => 'required',
            'client_token' => 'nullable',
            'card_token' => 'nullable',
            'save_card' => 'nullable',
        ];
    }

    private function getCurrentShoppingCart(): MealPlanCart
    {
        $mealPlan = MealPlan::current()->first();

        if ($mealPlan === null) {
            $mealPlan = MealPlan::orderBy('expires_on', 'desc')->first();
        }

        return MealPlanCart::with('items')
            ->where('cookie_id', '=', $this->getCookieId())
            ->where('meal_plan_id', '=', $mealPlan->id)
            ->whereNull('order_placed_on')
            ->orderBy('created_at', 'desc')
            ->firstOrFail();
    }

    private function getCurrentShoppingCartFormData()
    {
        $cart = $this->getCurrentShoppingCart();
        $quantity = $cart->items->pluck('quantity', 'meal_id')->toArray();

        $specialRequests = [
            'lowCarb' => $cart->hasSpecialRequest('LowCarb'),
            'extraProtein' => $cart->hasSpecialRequest('ExtraProtein'),
        ];

        $delivery = $cart->delivery;
        $deliveryAddress = json_decode($cart->delivery_address);
        $satellite = $cart->satellite_location_id;

        $promoCode = null;
        if ($cart->promoCode()->exists()) {
            $promoCode = $cart->promoCode->code;
        }

        return compact(
            'quantity',
            'specialRequests',
            'delivery',
            'deliveryAddress',
            'satellite',
            'promoCode',
        );
    }

    private function setCookieId()
    {
        Cookie::queue(Cookie::make($this->cookieIdKey, $this->getCookieId(), 525600));
    }

    private function getCookieId()
    {
        $cookieId = Cookie::get($this->cookieIdKey, Str::random(64));

        if (!$cookieId) {
            $cookieId = Str::random(64);
        }

        return $cookieId;
    }

    private function attachPromoCode($code, $storeLocationId)
    {
        $requestPromoCode = trim($code);

        $promosToTruncate = PromoCode::where('match_type', '=', 'starts_with')
            ->get()
            ->pluck('code')
            ->toArray();

        foreach ($promosToTruncate as $promoToTruncate) {
            if (Str::startsWith(strtolower($requestPromoCode), strtolower($promoToTruncate))) {
                $requestPromoCode = $promoToTruncate;
            }
        }

        $promoCode = PromoCode::where('code', '=', $requestPromoCode)
            ->active()
            ->whereHas('storeLocation', function ($query) use ($storeLocationId) {
                $query->where('code', '=', $storeLocationId);
            })
            ->orderBy('store_location_id', 'desc')
            ->first();

        // if a store promo code wans't found, check for global
        if ($promoCode === null) {
            $promoCode = PromoCode::where('code', '=', $requestPromoCode)
                ->active()
                ->whereNull('store_location_id')
                ->first();
        }

        if ($promoCode) {
            $cart = $this->getCurrentShoppingCart();
            $cart
                ->promoCode()
                ->associate($promoCode)
                ->save();
        } else {
            Log::warning(
                'Invalid coupon code ' . $code . ' attempted for store ' . $storeLocationId,
            );
        }

        return;
    }
}
