<?php

namespace App\Filament\Admin\Resources\KostumResource\Pages;

use App\Filament\Admin\Resources\KostumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKostum extends ListRecords
{
    protected static string $resource = KostumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
        ];
    }
}
