<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreLocation;
use App\Models\MealPlanCartItem;
use App\Models\MealPlanOrder;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class StoreLocationController extends Controller
{
    public function index(Request $request)
    {
        
        $query = StoreLocation::with([
            'availableSatellites' => function ($query) {
                $query
                    ->select('store_location_id', 'id', 'name', 'address', 'city', 'state', 'zip')
                    ->orderBy('name');
            },
        ])
            ->where('is_enabled', '=', 1)
            ->orderBy('state')
            ->orderBy('city');

        $mealPlanStoresOnly = filter_var(
            $request->input('meal-plan-stores-only'),
            FILTER_VALIDATE_BOOLEAN,
        );

        if ($mealPlanStoresOnly === true) {
            $query->where('is_meal_plan_ordering_enabled', '=', 1);
        }

        return $query->get();
    }

    public function show($code)
    {
        return StoreLocation::where('code', '=', $code)->firstOrFail();
    }
    public function carts()
    {
        $query = MealPlanCartItem::select('id','meal_plan_cart_id','meal_id','meal_name','cost','quantity')->get();
            $res = [
                'status' => 'success',
                'data' => $query,
            ];
        return response()->json($res, 200);
    }
    public function total()
    {
        $query = MealPlanOrder::select('id','meal_plan_cart_id','subtotal','tax','total')->get();
            $res = [
                'status' => 'success',
                'data' => $query,
            ];
        return response()->json($res, 200);
    }
    public function cart(Request $request)
    {
        $cart = MealPlanCartItem::find($request->id);
        if ($cart != null) {
            $carts_quantity = $cart->where('quantity', $cart->quantity)->first();
            
        }
        return response()->json(['result' => true, 'message' => 'Product is successfully cart'], 200);
        // $query = MealPlanCartItem::select('id','meal_plan_cart_id','meal_name','cost','quantity')->orderBy('id');
        // return $query->get();

    }

    // Api for email and token
    // public function createtoken(Request $request)
    // {

    //     $http = new \GuzzleHttp\Client();
    //     $email = $request->email;
        
    //     $response = $http->post('http://127.0.0.1:8000/createtoken', [
    //       'headers' => [
    //         'Accept' => 'application/json',
    //         'Content-Type' => 'application/json',
    //         'Qor-App-Key' => 'T6554252567241061980',
    //         'Qor-Client-Key' => '01dffeb784c64d098c8c691ea589eb82',
    //       ],
    //       'query'=> [
    //          'email'=>$email;
    //       ]
    //     ]);
        
    //     echo $response->getBody();

    // }
}
