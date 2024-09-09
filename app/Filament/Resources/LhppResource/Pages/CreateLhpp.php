<?php

namespace App\Filament\Resources\LhppResource\Pages;

use App\Filament\Resources\LhppResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLhpp extends CreateRecord
{
    protected static string $resource = LhppResource::class;
    protected function getRedirectUrl(): string
    {
    return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
