<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderRefundMail;
use App\Models\MealPlanOrder;
use App\Models\MealPlanRefund;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $storeLocationId = $request->get('storeLocationId', 23);

        return view('admin.order.index', compact('storeLocationId'));
    }

    public function show(MealPlanOrder $order)
    {
        return view('admin.order.show', compact('order'));
    }

    public function refund(MealPlanOrder $order)
    {
        return view('admin.order.refund', compact('order'));
    }

    public function processRefund(MealPlanOrder $order, Request $request)
    {
        $validated = $request->validate([
            'total_refund' => 'required|numeric',
            'net_refund' => 'required|numeric',
            'tax' => 'required|numeric',
            'tip_amount' => 'required|numeric',
            'satellite_fee' => 'required|numeric',
            'notes' => 'nullable',
            'notify_customer' => 'boolean',
        ]);

        $validated['meal_plan_order_id'] = $order->id;
        $validated['user_id'] = Auth::user()->id;

        $refund = new MealPlanRefund($validated);

        try {
            $refund = $order->refundCharge($refund);

            if (isset($validated['notify_customer'])) {
                Mail::to($order->email)->queue(new OrderRefundMail($refund));
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('status', 'Refund processed succesfully');
    }
}
