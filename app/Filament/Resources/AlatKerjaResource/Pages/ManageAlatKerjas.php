<?php

namespace App\Filament\Resources\AlatKerjaResource\Pages;

use App\Filament\Resources\AlatKerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAlatKerjas extends ManageRecords
{
    protected static string $resource = AlatKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color("primary"),
            // Actions\CreateAction::make(),
        ];
    }
}
