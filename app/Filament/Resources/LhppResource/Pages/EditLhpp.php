<?php

namespace App\Filament\Resources\LhppResource\Pages;

use App\Filament\Resources\LhppResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLhpp extends EditRecord
{
    protected static string $resource = LhppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
