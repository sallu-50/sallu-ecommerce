<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class OrderPaid extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()->where('status', 'completed');
    }
}
