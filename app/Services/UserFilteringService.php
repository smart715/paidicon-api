<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use http\Client\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UserFilteringService
{
    public function filter(array $filters): Collection
    {
        $dbQuery = User::query();
        foreach ($filters as $filter) {
            $this->applyFilter($filter, $dbQuery);
        }
        return $dbQuery->get();
    }

    private function applyFilter($filter, Builder $query)
    {
        switch (true) {
            case strpos($filter['property'], 'user.') === 0:
                $query->where(substr($filter['property'], 5), $filter['symbol'], $filter['value']);
                break;
            case strpos($filter['property'], 'order.') === 0:
                $query->whereHas('orders', function ($q) use ($filter) {
                    $q->where(substr($filter['property'], 6), $filter['symbol'], $filter['value']);
                });
                break;

            case strpos($filter['property'], 'transaction.') === 0:
                $query->whereHas('transactions', function ($q) use ($filter) {
                    $q->where(substr($filter['property'], 12), $filter['symbol'],$filter['value']);
                });
                break;

            case 'purchase_date':
                $query->where(function ($q) use ($filter) {
                    $q->whereHas('orders', function ($q) use ($filter) {
                        $q->where('created_at', $filter['symbol'], Carbon::parse($filter['value']));
                    })->orWhereHas('transactions', function ($q) use ($filter) {
                        $q->where('created_at', $filter['symbol'], Carbon::parse($filter['value']));
                    });
                });
                break;
            case 'order_count':
                $query->withCount('orders')
                    ->having('orders_count', $filter['symbol'], $filter['value']);
                break;
            case 'total_successful_transactions':
                $query->whereHas('transactions', function ($q) {
                    $q->where('status', 3);
                },               $filter['symbol'], $filter['value']);
                break;
            case 'package_name':
                $query->whereHas('orders', function ($q) use ($filter) {
                    $q->whereRelation('package', 'name', $filter['value']);
                });
                break;
            case 'total_transactions_referral':
                $query->whereHas('orders', function ($q) {
                    $q->whereRelation('transaction', 'status', 1);
                },               $filter['symbol'], $filter['value']);
                break;
            case 'total_transactions_refund':
                $query->whereHas('orders', function ($q) {
                    $q->whereRelation('transaction', 'status', 2);
                },               $filter['symbol'], $filter['value']);
                break;
            case 'total_transactions_order':
                $query->whereHas('orders', function ($q) {
                    $q->whereRelation('transaction', 'status', 3);
                },               $filter['symbol'], $filter['value']);
                break;
        }
    }
}
