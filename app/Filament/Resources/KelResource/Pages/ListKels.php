<?php

namespace App\Filament\Resources\KelResource\Pages;

use App\Filament\Resources\KelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListKels extends ListRecords
{
    protected static string $resource = KelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()->exports([
                ExcelExport::make()
                    ->withFilename('Desa-Kel')
                    ->fromModel()
            ])
        ];
    }
}
