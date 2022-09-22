<?php

namespace App\Http\Livewire\Admin\Order;

use App\Models\MealPlanOrder;
use App\Traits\ParsesDates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class OrdersTable extends DataTableComponent
{
    use ParsesDates;

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';
    public string $storeLocationId;

    public function columns(): array
    {
        return [
            Column::make('ID', 'transaction_id')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'transaction_status')
                ->sortable()
                ->format(fn($status) => Str::title($status)),
            Column::make('E-mail', 'email')
                ->sortable()
                ->searchable(),
            Column::make('First Name', 'first_name')
                ->sortable()
                ->searchable(),
            Column::make('Last Name', 'last_name')
                ->sortable()
                ->searchable(),
            Column::make('Total', 'total')
                ->sortable()
                ->searchable()
                ->format(fn($total) => '$' . $total),
            Column::make('Ordered At', 'created_at')
                ->sortable()
                ->format(fn($createdAt) => $this->toFormattedLocalDateTime($createdAt)),
        ];
    }

    public function filters(): array
    {
        return [
            'transaction_status' => Filter::make('Status')->select([
                '' => 'All Statuses',
                MealPlanOrder::STATUS_COMPLETED => 'Completed',
                MealPlanOrder::STATUS_REFUNDED => 'Refunded',
                MealPlanOrder::STATUS_CANCELED => 'Canceled',
                MealPlanOrder::STATUS_DECLINED => 'Declined',
            ]),
            'payment_type' => Filter::make('Payment Type')->select([
                '' => 'All Types',
                'online' => 'Online Only',
                'in-store' => 'In Store Only',
            ]),
        ];
    }

    public function query(): Builder
    {
        return MealPlanOrder::where('store_location_id', $this->storeLocationId)
            ->where('created_at', '>', Date::now()->subYear())
            ->when(
                $this->getFilter('payment_type'),
                fn($query, $paymentType) => $query->where('payment_type', $paymentType),
            )
            ->when(
                $this->getFilter('transaction_status'),
                fn($query, $transactionStatus) => $query->where(
                    'transaction_status',
                    $transactionStatus,
                ),
            );
    }

    public function setTableClass(): ?string
    {
        return 'table table-striped table-hover';
    }

    public function getTableRowUrl($row): string
    {
        return route('admin.orders.show', $row->id);
    }
}
