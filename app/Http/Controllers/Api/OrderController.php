<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MealPlanOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function clear(Request $request)
    {
        if (!$this->isAuthorized($request->user())) {
            return abort(401);
        }

        $params = $request->all();
        $order = MealPlanOrder::findOrFail($params['id']);

        if ($request->user()->isStore() && $this->orderBelongsToStore($request, $order) === false) {
            return abort(401);
        }

        if ($params['cleared'] === 'true') {
            $order->cleared_at = $params['timestamp'];
        } else {
            $order->cleared_at = null;
        }

        $order->save();
        return ['saved' => true];
    }

    private function isAuthorized($user)
    {
        return $user->isAdmin() || $user->isStore();
    }

    private function orderBelongsToStore($request, $order)
    {
        return $request->user()->storeLocation->code === $order->cart->storeLocation->code;
    }
}
