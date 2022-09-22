<?php

namespace App\Http\Controllers\Store;

use App\Http\Requests\StorePromoCode;
use App\Mail\PromoCodeApprovalRequest;
use App\Models\PromoCode;
use App\Traits\RendersTableButtons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PromoCodeController extends StoreBaseController
{
    use RendersTableButtons;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('store.promo-codes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $promoCode = new PromoCode();
        $storeLocation = $this->currentStoreLocation($request);
        return view('store.promo-codes.create', compact('promoCode', 'storeLocation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePromoCode $request)
    {
        $input = $request->validated();
        $input['store_location_id'] = $this->currentStoreLocation($request)->id;
        $input['is_approved'] = false;

        PromoCode::create($input);

        Mail::to('bonseye@cleaneatz.com')
            ->cc('gary@curlytech.com')
            ->queue(new PromoCodeApprovalRequest());

        return redirect()
            ->route('store.promo-codes.index', $this->currentStoreAttribute($request))
            ->with('status', 'Promo code has been submitted for approval.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $storeCode, PromoCode $promoCode)
    {
        $this->authorize('delete', $promoCode);

        $promoCode->delete();

        return redirect()
            ->route('store.promo-codes.index', $this->currentStoreAttribute($request))
            ->with('status', 'Promo code has been deleted.');
    }

    public function data(Request $request)
    {
        return datatables()
            ->of(PromoCode::where('store_location_id', $this->currentStoreLocation($request)->id))
            ->editColumn('is_approved', function ($promoCode) {
                return $promoCode->is_approved ? 'Yes' : 'No';
            })
            ->editColumn('discount_amount', function ($promoCode) {
                return empty($promoCode->discount_amount)
                    ? ''
                    : '$' . number_format($promoCode->discount_amount, 2);
            })
            ->editColumn('discount_percent', function ($promoCode) {
                return empty($promoCode->discount_percent)
                    ? ''
                    : number_format($promoCode->discount_percent) . '%';
            })
            ->editColumn('start_on', function ($promoCode) {
                return empty($promoCode->start_on) ? '' : $promoCode->start_on->format('m/d/Y');
            })
            ->editColumn('end_on', function ($promoCode) {
                return empty($promoCode->end_on) ? '' : $promoCode->end_on->format('m/d/Y');
            })
            ->toJson();
    }
}
