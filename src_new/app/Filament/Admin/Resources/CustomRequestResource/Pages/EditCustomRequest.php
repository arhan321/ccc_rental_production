<?php

namespace App\Filament\Admin\Resources\CustomRequestResource\Pages;

use App\Filament\Admin\Resources\CustomRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomRequest extends EditRecord
{
    protected static string $resource = CustomRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
