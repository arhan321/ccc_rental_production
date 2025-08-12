<?php

namespace App\Filament\Admin\Resources\CustomRequestResource\Pages;

use App\Filament\Admin\Resources\CustomRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomRequest extends CreateRecord
{
    protected static string $resource = CustomRequestResource::class;
}
