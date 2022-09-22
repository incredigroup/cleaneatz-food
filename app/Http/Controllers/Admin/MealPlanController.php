<?php

namespace App\Http\Controllers\Admin;

use App\{Models\Meal, Models\MealPlan};
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMealPlan;
use App\Traits\ParsesDates;
use App\Traits\RendersTableButtons;
use Illuminate\Http\Request;

class MealPlanController extends Controller
{
    use RendersTableButtons;
    use ParsesDates;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.meal-plans.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $numberOfMeals = $request->get('meal-count') ?? 6;
        $mealPlan = new MealPlan;
        $meals = $this->getMealOptions();

        return view('admin.meal-plans.create', compact('mealPlan', 'meals', 'numberOfMeals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMealPlan $request)
    {
        $input = $request->validated();

        $mealPlan = MealPlan::create($input);
        $meals = Meal::whereIn('id', $input['meal_id'])->get();
        $mealPlan->items()->attach($meals);

        return redirect()->route('admin.meal-plans.index')
            ->with('status', 'Meal plan has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MealPlan  $mealPlan
     * @return \Illuminate\Http\Response
     */
    public function show(MealPlan $mealPlan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MealPlan  $mealPlan
     * @return \Illuminate\Http\Response
     */
    public function edit(MealPlan $mealPlan, Request $request)
    {
        $numberOfMeals = $request->get('meal-count') ?? 6;
        $meals = $this->getMealOptions();

        $mealPlan->available_on = $this->toLocalDateTime($mealPlan->available_on);
        $mealPlan->expires_on = $this->toLocalDateTime($mealPlan->expires_on);

        return view('admin.meal-plans.edit', compact('mealPlan', 'numberOfMeals', 'meals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MealPlan  $mealPlan
     * @return \Illuminate\Http\Response
     */
    public function update(StoreMealPlan $request, MealPlan $mealPlan)
    {
        $input = $request->validated();
        $mealPlan->update($input);
        $meals = Meal::whereIn('id', $input['meal_id'])->get();

        $mealPlan->items()->sync($meals);

        return redirect()->route('admin.meal-plans.index')
            ->with('status', 'Meal plan has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MealPlan  $mealPlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(MealPlan $mealPlan)
    {
        $mealPlan->delete();

        return redirect()->route('admin.meal-plans.index')
            ->with('status', 'Meal plan has been deleted.');
    }

    public function data()
    {
        return datatables()->of(MealPlan::select(['id', 'name', 'available_on', 'expires_on']))
            ->editColumn('available_on', function ($mealPlan) {
                return $this->toFormattedLocalDateTime($mealPlan->available_on);
            })
            ->editColumn('expires_on', function ($mealPlan) {
                return $this->toFormattedLocalDateTime($mealPlan->expires_on);
            })
            ->addColumn('actions', function ($mealPlan) {
                return $this->renderEditDeleteButtons('admin.meal-plans', $mealPlan->id);
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    private function getMealOptions()
    {
        return Meal::orderBy('created_at', 'desc')
            ->orderBy('name')
            ->where('is_add_on_item', '=', false)
            ->limit(50)
            ->get()
            ->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name'] . ' - ' . $this->toFormattedLocalDate($item['created_at'])];
        });
    }
}
