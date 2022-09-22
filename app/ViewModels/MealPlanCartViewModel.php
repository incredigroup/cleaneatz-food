<?php

namespace App\ViewModels;

use App\{Models\MealPlanCart};
use Auth;
use Spatie\ViewModels\ViewModel;

class MealPlanCartViewModel extends ViewModel
{
    public $cart = null;

    public function __construct(MealPlanCart $cart)
    {
        $this->cart = $cart;
    }

    public function storedPaymentMethods()
    {
        $fields = ['card_last_4', 'card_exp_month', 'card_exp_year', 'card_brand', 'card_token'];

        if (!Auth::check()) {
            return [];
        }

        return Auth::user()
            ->paymentMethods()
            ->where('store_location_id', '=', $this->cart->store_location_id)
            ->orderBy('updated_at', 'desc')
            ->get($fields);
    }

    public function defaultEmail()
    {
        return $this->defaultAuthPropery('email');
    }

    public function defaultName()
    {
        return Auth::check() ? Auth::user()->getFullName() : null;
    }

    public function defaultFirstName()
    {
        return Auth::check() ? Auth::user()->first_name : null;
    }

    public function defaultLastName()
    {
        return Auth::check() ? Auth::user()->last_name : null;
    }

    public function satellite()
    {
        if ($this->cart->satelliteLocation()->doesntExist()) {
            return null;
        }

        return $this->cart->satelliteLocation->only('name', 'address', 'city', 'state', 'zip');
    }

    public function editCartUrl()
    {
        return $this->cart->is_custom
            ? route('site.custom-menu')
            : route('site.meal-plan-menu', ['edit' => true]);
    }

    public function onlinePaymentsOnly()
    {
        return $this->cart->satelliteLocation()->exists() ||
            $this->cart->delivery ||
            $this->cart->is_custom;
    }

    public function inStorePaymentsOnly()
    {
        return !$this->cart->storeLocation->gateway_merchant_token ||
            !$this->cart->storeLocation->is_online_payment_enabled;
    }

    private function defaultAuthPropery($property)
    {
        return old($property, Auth::check() ? Auth::user()->$property : null);
    }
}
