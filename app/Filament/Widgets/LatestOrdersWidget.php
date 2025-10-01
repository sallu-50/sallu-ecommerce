<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource;
use App\Models\Order;

class LatestOrdersWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function getTableQuery(): Builder
    {
        return Order::query()->latest()->take(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('customer.name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('total')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Order Date')
                ->dateTime(),
        ];
    }
}
