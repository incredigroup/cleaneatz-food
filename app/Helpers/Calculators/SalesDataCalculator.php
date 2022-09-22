<?php

namespace App\Helpers\Calculators;

interface SalesDataCalculator
{
    public function locationIds();

    public function totals(int $storeLocationId);

    public function refunds(int $storeLocationId);

    public function categoryTotals(int $storeLocationId);
}
