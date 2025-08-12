<?php

namespace App\Filament\Admin\Resources\ProductScheduleResource\Pages;

use App\Filament\Admin\Resources\ProductScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductSchedules extends ListRecords
{
    protected static string $resource = ProductScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
