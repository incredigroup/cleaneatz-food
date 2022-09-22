<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeal;
use App\Models\Meal;
use App\Traits\RendersTableButtons;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use NumberFormatter;

class AddOnController extends Controller
{
    use RendersTableButtons;

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.add-ons.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $meal = new Meal;
        $variants = [];

        return view('admin.add-ons.create', compact('meal', 'variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMeal $request
     * @return RedirectResponse
     */
    public function store(StoreMeal $request): RedirectResponse
    {
        $this->saveAddOnItems($request);

        return redirect()->route('admin.add-ons.index')
            ->with('status', 'Add on item has been created.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Meal $addOn
     * @return View
     */
    public function edit(Meal $addOn): View
    {
        $meal = $addOn;

        $hasVariants = Meal::where('group_name', '=', $meal->group_name)
            ->where('id', '!=', $meal->id)
            ->exists();

        $variants = [];
        if ($hasVariants) {
            $variants = Meal::where('group_name', '=', $meal->group_name)->get();
        }

        return view('admin.add-ons.edit', compact('addOn', 'meal', 'variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreMeal $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(StoreMeal $request, int $id): RedirectResponse
    {
        $meal = Meal::findOrFail($id);

        $hasVariants = Meal::where('group_name', '=', $meal->group_name)
            ->where('id', '!=', $meal->id)
            ->exists();

        // check to see if this request is going to have new variants
        if ($hasVariants === false) {
            $hasVariants = collect($request->get('newVariant'))->whereNotNull()->count() > 0;
        }

        if ($hasVariants === false) {
            $addOn = $request->validated();
            $addOn['description'] = $addOn['group_desc'];

            $meal->update($addOn);
        } else {
            // Meal::where('group_name', '=', $meal->group_name)->delete();
            $this->updateAddOnVariants($request, $meal->image_url);
        }

        return redirect()->route('admin.add-ons.index')
            ->with('status', 'Add-on item has been updated.');
    }

    private function updateAddOnVariants($request, $imageUrl = false) {
        $variant = $request->validated();
        $variant['is_add_on_item'] = true;
        $variant['group_name'] = Str::slug($variant['name']);

        if ($imageUrl) {
            $variant['image_url'] = $imageUrl;
        }

        if ($request->get('variant')) {
            forEach($request->get('variant') as $id => $variantName) {
                $variant['description'] = $variantName;
                $meal = Meal::findOrFail($id);
                if ($variantName) {
                    $meal->update($variant);
                } else {
                    $meal->delete();
                }
            }
        }

        if ($request->get('newVariant')) {
            forEach($request->get('newVariant') as $variantName) {
                if (strlen($variantName) > 0) {
                    $variant['description'] = $variantName;
                    Meal::create($variant);
                }
            }
        }

    }

    private function saveAddOnItems($request, $imageUrl = false) {
        $meal = $request->validated();
        $meal['is_add_on_item'] = true;
        $meal['group_name'] = Str::slug($meal['name']);

        if ($imageUrl) {
            $meal['image_url'] = $imageUrl;
        }

        $hasVariants = false;

        // Create a meal object for each variant
        forEach($request->get('newVariant') as $variantName) {
            if (strlen($variantName) > 0) {
                $hasVariants = true;
                $variant = $meal;
                $variant['description'] = $variantName;
                Meal::create($variant);
            }
        }

        // if there are no variants, create a standard add-on meal
        if ($hasVariants === false) {
            $meal['description'] = $meal['group_desc'];
            Meal::create($meal);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {

        $meal = Meal::findOrFail($id);

        Meal::where('is_add_on_item', '=', true)
            ->where('group_name', '=', $meal->group_name)
            ->delete();

        return redirect()->route('admin.add-ons.index')
            ->with('status', 'Add-on has been deleted.');
    }

    public function data()
    {
        $addOns = Meal::select(DB::raw('MIN(id) as id, name, group_desc, price_override'))
            ->where('is_add_on_item', '=', true)
            ->groupBy('group_name', 'name', 'group_desc', 'price_override');

        return datatables()->of($addOns)
            ->editColumn('group_desc', function ($meal) {
                if (strlen($meal->group_desc) > 45) {
                    return substr($meal->group_desc, 0, 45) . '...';
                }
                return $meal->group_desc;
            })
            ->editColumn('price_override', function($meal) {
                $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                return $formatter->formatCurrency($meal->price_override, 'USD');
            })
            ->addColumn('actions', function ($meal) {
                return $this->renderEditDeleteButtons('admin.add-ons', $meal->id);
            })
            ->rawColumns(['actions'])
            ->toJson();
    }
}
