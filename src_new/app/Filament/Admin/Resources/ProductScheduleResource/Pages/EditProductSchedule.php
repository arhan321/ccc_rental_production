<?php

namespace App\Filament\Admin\Resources\ProductScheduleResource\Pages;

use App\Filament\Admin\Resources\ProductScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductSchedule extends EditRecord
{
    protected static string $resource = ProductScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
