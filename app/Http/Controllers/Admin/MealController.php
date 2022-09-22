<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeal;
use App\Models\Meal;
use App\Traits\ParsesDates;
use App\Traits\RendersTableButtons;

class MealController extends Controller
{
    use RendersTableButtons, ParsesDates;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.meals.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $meal = new Meal();
        return view('admin.meals.create', compact('meal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMeal $request)
    {
        Meal::create($request->validated());

        return redirect()
            ->route('admin.meals.index')
            ->with('status', 'Meal has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function show(Meal $meal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function edit(Meal $meal)
    {
        return view('admin.meals.edit', compact('meal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function update(StoreMeal $request, Meal $meal)
    {
        $meal->update($request->validated());

        return redirect()
            ->route('admin.meals.index')
            ->with('status', 'Meal has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meal $meal)
    {
        $meal->delete();

        return redirect()
            ->route('admin.meals.index')
            ->with('status', 'Meal has been deleted.');
    }

    public function data()
    {
        return datatables()
            ->of(Meal::where('is_add_on_item', '=', false))
            ->editColumn('description', function ($mealPlan) {
                return substr($mealPlan->description, 0, 30) . '...';
            })
            ->editColumn('created_at', function ($meal) {
                return $this->toFormattedLocalDate($meal->created_at);
            })
            ->addColumn('actions', function ($meal) {
                return $this->renderEditDeleteButtons('admin.meals', $meal->id);
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function deleteImage(Meal $meal)
    {
        $meal->update(['image_url' => null]);

        return redirect()
            ->route('admin.meals.edit', compact('meal'))
            ->with('status', 'Meal image has been removed.');
    }
}
