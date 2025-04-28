<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB; // Add this line

class SalesChartWidget extends Widget
{
    protected static string $view = 'filament.widgets.sales-chart-widget';


    public function getSalesData(): array
    {
        // Get the sales data (total sales per date)
        $salesData = Order::where('status', 'completed')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as sales'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Calculate total sales and total number of orders
        $totalSales = Order::sum('total');
        $totalOrders = Order::count();

        return compact('salesData', 'totalSales', 'totalOrders');
    }

    public function render(): View
    {
        $data = $this->getSalesData();

        return view(static::$view, [
            'salesData' => $data['salesData'],
            'totalSales' => $data['totalSales'],
            'totalOrders' => $data['totalOrders'],
        ]);
    }
}
