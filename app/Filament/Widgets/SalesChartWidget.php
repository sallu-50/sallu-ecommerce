<?php
// app/Filament/Widgets/SalesChartWidget.php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class SalesChartWidget extends Widget
{
    protected static string $view = 'filament.widgets.sales-chart-widget'; // The view that will display the chart

    public function getSalesData(): Collection
    {
        // Fetch the sales data, you can modify this to your actual sales query
        return Order::selectRaw('DATE(created_at) as date, SUM(total) as total_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    // Make sure this method returns a View instance
    public function render(): View
    {
        // Pass the sales data to the view
        return view(static::$view, [
            'salesData' => $this->getSalesData(),
        ]);
    }
}
