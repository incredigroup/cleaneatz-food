<?php

Route::prefix('store')
    ->name('store.')
    ->namespace('Store')
    ->middleware('auth.store')
    ->group(function () {
        Route::get('/', 'HomeController@index')->name('home');

        Route::get('{store_code}/orders', 'OrderController@index')->name('orders.index');

        Route::get(
            '{store_code}/orders/satellite/{satellite_id}',
            'OrderController@satellite',
        )->name('orders.satellite');

        Route::get('{store_code}/orders/delivery', 'OrderController@delivery')->name(
            'orders.delivery',
        );

        Route::get('{store_code}/orders/custom', 'OrderController@custom')->name('orders.custom');

        Route::get('{store_code}/dashboard', 'DashboardController@index')->name('dashboard.index');

        Route::get(
            '{store_code}/dashboard/data-reporting/{start_on}/{end_on}',
            'DashboardController@salesReporting',
        );

        Route::get('{store_code}/promo-codes', 'PromoCodeController@index')->name(
            'promo-codes.index',
        );

        Route::get('{store_code}/promo-codes/data', 'PromoCodeController@data')->name(
            'promo-codes.data',
        );

        Route::get('{store_code}/promo-codes/create', 'PromoCodeController@create')->name(
            'promo-codes.create',
        );

        Route::post('{store_code}/promo-codes', 'PromoCodeController@store')->name(
            'promo-codes.store',
        );

        Route::delete('{store_code}/promo-codes/{promo_code}', 'PromoCodeController@destroy')->name(
            'promo-codes.destroy',
        );

        Route::get('{store_code}/satellite-locations', 'SatelliteLocationController@index')->name(
            'satellite-locations.index',
        );

        Route::get(
            '{store_code}/satellite-locations/data',
            'SatelliteLocationController@data',
        )->name('satellite-locations.data');

        Route::get(
            '{store_code}/satellite-locations/create',
            'SatelliteLocationController@create',
        )->name('satellite-locations.create');

        Route::post('{store_code}/satellite-locations', 'SatelliteLocationController@store')->name(
            'satellite-locations.store',
        );

        Route::get(
            '{store_code}/satellite-locations/{id}/edit',
            'SatelliteLocationController@edit',
        )->name('satellite-locations.edit');

        Route::put(
            '{store_code}/satellite-locations/{id}',
            'SatelliteLocationController@update',
        )->name('satellite-locations.update');

        Route::delete(
            '{store_code}/satellite-locations/{id}',
            'SatelliteLocationController@destroy',
        )->name('satellite-locations.destroy');

        Route::get(
            '{store_code}/reports/satellite-location/orders',
            'ReportSatelliteLocationController@orders',
        )->name('reports.satellite-location.orders');

        Route::get(
            '{store_code}/reports/satellite-location/orders/data',
            'ReportSatelliteLocationController@ordersData',
        )->name('reports.satellite-location.orders.data');

        Route::get(
            '{store_code}/reports/satellite-location/orders/export',
            'ReportSatelliteLocationController@ordersExport',
        )->name('reports.satellite-location.orders.export');

        Route::get(
            '{store_code}/reports/satellite-location/totals',
            'ReportSatelliteLocationController@totals',
        )->name('reports.satellite-location.totals');

        Route::get(
            '{store_code}/reports/customer/sales-trends',
            'ReportCustomerController@salesTrends',
        )->name('reports.customer.sales-trends');

        Route::get(
            '{store_code}/reports/customer/sales-trends/data',
            'ReportCustomerController@salesTrendsData',
        )->name('reports.customer.sales-trends.data');

        Route::get(
            '{store_code}/reports/customer/sales-trends/export',
            'ReportCustomerController@salesTrendsExport',
        )->name('reports.customer.sales-trends.export');

        Route::get('{store_code}/reports/promo-code/uses', 'ReportPromoCodeController@uses')->name(
            'reports.promo-code.uses',
        );
    });
