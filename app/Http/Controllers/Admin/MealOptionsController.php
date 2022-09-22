<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MealOptionsController extends Controller
{
    public static $options = ['upcoming_meals_msg', 'meals_available_msg', 'meal_plan_pause_msg'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $options = [];
        foreach (self::$options as $optionKey) {
            $options[$optionKey] = option($optionKey);
        }

        return view('admin.meal-options.index', compact('options'));
    }

    public function update(Request $request)
    {
        $optionsToSave = [];
        foreach (self::$options as $optionKey) {
            $optionsToSave[$optionKey] = trim($request->get($optionKey)) ?: '';
        }

        option($optionsToSave);

        return redirect()
            ->route('admin.options.meals.index')
            ->with('status', 'Meal options have been updated.');
    }
}
