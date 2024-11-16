<?php

namespace App\Filament\Resources\AlatKerjaResource\Pages;

use App\Filament\Resources\AlatKerjaResource;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ManageAlatKerjas extends ManageRecords
{
    protected static string $resource = AlatKerjaResource::class;

    protected function getHeaderActions(): array
    {
        $role = auth()->user()->roles->pluck('id');
        if (($role->contains(1))) {
            return [
                Actions\CreateAction::make('Buat'),
                ExcelImportAction::make()
                    ->color("primary"),
                ExportAction::make()->exports([
                    ExcelExport::make()
                        ->withFilename('Alat Kerja')
                        ->fromModel()
                ])
            ];
        } else {
            return [];
        }
    }
}
