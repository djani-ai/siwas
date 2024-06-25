<?php

namespace App\Filament\Resources\SptResource\Pages;

use App\Filament\Resources\SptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpt extends EditRecord
{
    protected static string $resource = SptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
    return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
