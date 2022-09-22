<?php

use App\Http\Controllers\Admin\OrderController;

Route::prefix('admin')
    ->name('admin.')
    ->namespace('Admin')
    ->middleware('auth.admin')
    ->group(function () {
        Route::get('/', 'HomeController@index')->name('home');

        Route::get('meals/data', 'MealController@data')->name('meals.data');
        Route::get('add-ons/data', 'AddOnController@data')->name('add-ons.data');

        Route::delete('meals/{meal}/delete-image', 'MealController@deleteImage')->name(
            'meals.delete-image',
        );

        Route::get('meal-plans/data', 'MealPlanController@data')->name('meal-plans.data');

        Route::get('options/meals', 'MealOptionsController@index')->name('options.meals.index');

        Route::put('options/meals', 'MealOptionsController@update')->name('options.meals.update');

        Route::get('options/custom-meals', 'CustomMealOptionsController@index')->name(
            'options.custom-meals.index',
        );

        Route::put('options/custom-meals', 'CustomMealOptionsController@update')->name(
            'options.custom-meals.update',
        );

        Route::get(
            'satellite-locations/unapproved',
            'SatelliteLocationController@unapproved',
        )->name('satellite-locations.unapproved');

        Route::post(
            'satellite-locations/approve/{id}',
            'SatelliteLocationController@approve',
        )->name('satellite-locations.approve');

        Route::get('satellite-locations/data', 'SatelliteLocationController@data')->name(
            'satellite-locations.data',
        );

        Route::get('promo-codes/unapproved', 'PromoCodeController@unapproved')->name(
            'promo-codes.unapproved',
        );

        Route::post('promo-codes/approve/{id}', 'PromoCodeController@approve')->name(
            'promo-codes.approve',
        );

        Route::get('promo-codes/data', 'PromoCodeController@data')->name('promo-codes.data');

        Route::get('users/data/{role}', 'UserController@data')->name('users.data');

        Route::get('users/password/{user}', 'UserController@password')->name('users.password.edit');

        Route::put('users/password/{user}', 'UserController@updatePassword')->name(
            'users.password.update',
        );

        Route::get(
            'reports/meal-plan/meal-plan-tally',
            'Reports\MealPlanController@mealPlanTally',
        )->name('reports.meal-plan.meal-plan-tally');

        Route::get(
            'reports/meal-plan/meal-plan-tally/data',
            'Reports\MealPlanController@mealPlanTallyData',
        )->name('reports.meal-plan.meal-plan-tally.data');

        Route::get(
            'reports/meal-plan/meal-plan-tally/export',
            'Reports\MealPlanController@mealPlanTallyExport',
        )->name('reports.meal-plan.meal-plan-tally.export');

        Route::get(
            'reports/meal-plan/meal-plan-tally-summary',
            'Reports\MealPlanController@mealPlanTallySummary',
        )->name('reports.meal-plan.meal-plan-tally-summary');

        Route::get(
            'reports/meal-plan/customers-by-meal-plan',
            'Reports\MealPlanController@customersByMealPlan',
        )->name('reports.meal-plan.customers-by-meal-plan');

        Route::get(
            'reports/meal-plan/special-request-summary',
            'Reports\MealPlanController@specialRequestSummary',
        )->name('reports.meal-plan.special-request-summary');

        Route::get(
            'reports/meal-plan/customers-by-meal-plan/data',
            'Reports\MealPlanController@customersByMealPlanData',
        )->name('reports.meal-plan.customers-by-meal-plan.data');

        Route::get(
            'reports/meal-plan/customers-by-meal-plan/export',
            'Reports\MealPlanController@customersByMealPlanExport',
        )->name('reports.meal-plan.customers-by-meal-plan.export');

        Route::get(
            'reports/orders/by-satellite-location',
            'Reports\OrderController@bySatelliteLocation',
        )->name('reports.orders.by-satellite-location');

        Route::get(
            'reports/orders/non-revenue-charges',
            'Reports\OrderController@nonRevenueCharges',
        )->name('reports.orders.non-revenue-charges');

        Route::get(
            'reports/orders/non-revenue-charges/export',
            'Reports\OrderController@nonRevenueChargesExport',
        )->name('reports.orders.non-revenue-charges.export');

        Route::get(
            'reports/promo-code/promo-code-uses',
            'Reports\PromoCodeController@promoCodeUses',
        )->name('reports.promo-code.promo-code-uses');

        Route::get(
            'reports/promo-code/promo-code-uses/data',
            'Reports\PromoCodeController@promoCodeUsesData',
        )->name('reports.promo-code.promo-code-uses.data');

        Route::get('reports/tips/tips', 'Reports\TipsController@tips')->name('reports.tips.tips');

        Route::get('reports/tips/tips-by-store', 'Reports\TipsController@tipsByStore')->name(
            'reports.tips.tips-by-store',
        );

        Route::get(
            'reports/newsletter-signups/newsletter-signups-by-store/{audience?}',
            'Reports\NewsletterSignupsController@signupsByStore',
        )->name('reports.signups.signups-by-store');

        Route::get('reports/sales-reports', 'Reports\SalesReportController@index')->name(
            'reports.sales-reports',
        );

        Route::get('/sales-reports/create/{startOn}', 'Reports\SalesReportController@create')->name(
            'sales-reports.create',
        );

        Route::get('/sales-reports/{salesReport}', 'Reports\SalesReportController@view')->name(
            'sales-reports.view',
        );

        Route::delete('/sales-reports/{salesReport}', 'Reports\SalesReportController@delete')->name(
            'sales-reports.delete',
        );

        Route::get(
            '/sales-reports/export-excel/{salesReport}',
            'Reports\SalesReportController@exportExcel',
        )->name('sales-reports.export.excel');

        Route::get(
            '/sales-reports/export-ach/{salesReport}',
            'Reports\SalesReportController@exportACH',
        )->name('sales-reports.export.ach');

        Route::get(
            'reports/sales-report-dashboard/categories',
            'Reports\SalesReportDashboardController@categories',
        )->name('reports.sales-report-dashboard.categories');

        Route::get(
            'reports/sales-report-dashboard/categories/data',
            'Reports\SalesReportDashboardController@categoriesData',
        );

        Route::get(
            'reports/sales-report-dashboard/categories/chart-data',
            'Reports\SalesReportDashboardController@chartData',
        );

        Route::get('store-options/index', 'StoreOptionController@index')->name(
            'store-options.index',
        );

        Route::get('payeezy-setup', 'PayeezySetupController@index')->name('payeezy-setup.index');

        Route::post('payeezy-setup/place-order', 'PayeezySetupController@placeOrder')->name(
            'payeezy-setup.place-order',
        );

        Route::resource('orders', 'OrderController')->only(['index', 'show']);

        Route::get('orders/{order}/refund', 'OrderController@refund')->name('orders.refund');

        Route::post('orders/{order}/refund', 'OrderController@processRefund')->name(
            'orders.process-refund',
        );

        Route::resources([
            'meals' => 'MealController',
            'meal-plans' => 'MealPlanController',
            'add-ons' => 'AddOnController',
            'satellite-locations' => 'SatelliteLocationController',
            'promo-codes' => 'PromoCodeController',
            'users' => 'UserController',
        ]);
    });
