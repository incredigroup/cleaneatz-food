<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use Illuminate\Http\Request;

class CustomMealOptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customOptions = option('custom_meal_options');
        foreach ($customOptions as $k => $v) {
            $customOptions[$k] = $this->optionArrayToText($v);
        }

        return view('admin.custom-meal-options.index', compact('customOptions'));
    }

    public function update(Request $request)
    {
        $customOptions = [];
        foreach (Meal::$customOptions as $option) {
            $customOptions[$option] = $this->optionTextToArray($request->get($option));
        }

        option(['custom_meal_options' => $customOptions]);

        return redirect()
            ->route('admin.options.custom-meals.index')
            ->with('status', 'Custom meal options have been updated.');
    }

    private function optionArrayToText($optionArray)
    {
        $optionText = '';
        foreach ($optionArray as $item) {
            $optionText .= $item['label'];
            $optionText .= $item['cost'] ? '+' . $item['cost'] : '';
            $optionText .= "\n";
        }

        return $optionText;
    }

    private function optionTextToArray($optionText)
    {
        return collect(explode("\n", $optionText))
            ->map(function ($optionTextRow) {
                $optionTextRowItem = explode('+', trim($optionTextRow));

                $label = trim($optionTextRowItem[0]);

                $cost = isset($optionTextRowItem[1])
                    ? trim(str_replace('$', '', $optionTextRowItem[1]))
                    : 0;
                $cost = is_numeric($cost) ? floatval($cost) : 0;

                return compact('label', 'cost');
            })
            ->toArray();
    }
}
