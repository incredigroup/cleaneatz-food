<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromoCode;
use App\Models\PromoCode;
use App\Traits\RendersTableButtons;

class PromoCodeController extends Controller
{
    use RendersTableButtons;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.promo-codes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $promoCode = new PromoCode();
        return view('admin.promo-codes.create', compact('promoCode'));
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

        PromoCode::create($input);

        return redirect()
            ->route('admin.promo-codes.index')
            ->with('status', 'Promo code has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function show(PromoCode $promoCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function edit(PromoCode $promoCode)
    {
        return view('admin.promo-codes.edit', compact('promoCode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function update(StorePromoCode $request, PromoCode $promoCode)
    {
        $input = $request->validated();

        $promoCode->update($input);

        return redirect()
            ->route('admin.promo-codes.index')
            ->with('status', 'Promo code has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();

        return redirect()
            ->route('admin.promo-codes.index')
            ->with('status', 'Promo code has been deleted.');
    }

    public function unapproved()
    {
        $promoCodes = PromoCode::unapproved()->get();

        return view('admin.promo-codes.unapproved', compact('promoCodes'));
    }

    public function approve($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        $promoCode->is_approved = true;
        $promoCode->save();

        return redirect()
            ->route('admin.promo-codes.unapproved')
            ->with('status', 'Promo code has been approved.');
    }

    public function data()
    {
        return datatables()
            ->of(PromoCode::with('storeLocation')->select('promo_codes.*'))
            ->editColumn('store_location.city', function ($promoCode) {
                return $promoCode->isGlobal() ? 'Global' : $promoCode->storeLocation?->locale;
            })
            ->editColumn('discount_amount', function ($promoCode) {
                return empty($promoCode->discount_amount)
                    ? ''
                    : '$' . number_format($promoCode->discount_amount, 2);
            })
            ->editColumn('code', function ($promoCode) {
                return $promoCode->match_type === 'equals'
                    ? $promoCode->code
                    : $promoCode->code . '* (Prefix)';
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
            ->addColumn('actions', function ($promoCode) {
                return $this->renderEditDeleteButtons('admin.promo-codes', $promoCode->id);
            })
            ->rawColumns(['actions'])
            ->toJson();
    }
}
