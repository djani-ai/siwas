<?php

namespace App\Filament\Resources\LhppResource\Pages;

use App\Filament\Resources\LhppResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLhpps extends ListRecords
{
    protected static string $resource = LhppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
