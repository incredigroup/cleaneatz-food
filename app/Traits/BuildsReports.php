<?php

namespace App\Traits;

use App\Models\MealPlan;
use Illuminate\Http\Request;

trait BuildsReports
{
    protected function reportInputs($request, $defaultDateRange)
    {
        $params = [
            'start_date' => $request->query->get('start_date'),
            'end_date' => $request->query->get('end_date'),
            'store_code' => $request->query->has('store_location')
                ? $request->query->get('store_location')->code
                : null,
        ];

        return [
            'routeParams' => $params,
            'defaultStart' => $defaultDateRange[0],
            'defaultEnd' => $defaultDateRange[1],
            'activeStart' => $request->get('start_date', $defaultDateRange[0]),
            'activeEnd' => $request->get('end_date', $defaultDateRange[1]),
        ];
    }

    protected function bindQueryTimeframe($request, $query, $defaultDateRange, $col = 'created_at')
    {
        $query->where(
            $col,
            '>=',
            $this->startOfDayGlobalDateTime($request->get('start_date', $defaultDateRange[0])),
        );

        $query->where(
            $col,
            '<=',
            $this->endOfDayGlobalDateTime($request->get('end_date', $defaultDateRange[1])),
        );
    }

    protected function recentMealPlanSelection(Request $request)
    {
        $mealPlans = MealPlan::orderBy('id', 'desc')
            ->limit(57)
            ->get();
        $mealPlan = $request->has('mealPlanId')
            ? $mealPlans->firstWhere('id', $request->get('mealPlanId'))
            : $mealPlans[0];

        return compact('mealPlans', 'mealPlan');
    }
}
