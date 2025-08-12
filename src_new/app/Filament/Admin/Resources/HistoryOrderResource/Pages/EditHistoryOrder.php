<?php

namespace App\Filament\Admin\Resources\HistoryOrderResource\Pages;

use App\Filament\Admin\Resources\HistoryOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHistoryOrder extends EditRecord
{
    protected static string $resource = HistoryOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
