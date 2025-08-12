<?php

namespace App\Filament\Admin\Resources\HistoryOrderResource\Pages;

use App\Filament\Admin\Resources\HistoryOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHistoryOrders extends ListRecords
{
    protected static string $resource = HistoryOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
