<?php

namespace App\Filament\Admin\Resources\CustomRequestResource\Pages;

use App\Filament\Admin\Resources\CustomRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomRequests extends ListRecords
{
    protected static string $resource = CustomRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
