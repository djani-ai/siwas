<?php

namespace App\Filament\Resources\TpsResource\Pages;

use App\Filament\Resources\TpsResource;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListTps extends ListRecords
{
    protected static string $resource = TpsResource::class;

    protected function getHeaderActions(): array
    {
        $role = auth()->user()->roles->pluck('id');
        if (($role->contains(1))) {
            return [
                ExcelImportAction::make()
                    ->color("primary"),
                Actions\CreateAction::make(),
                ExportAction::make()->exports([
                    ExcelExport::make()
                        ->withFilename('Nomor-TPS')
                        ->fromModel()
                ])
            ];
        } else {
            return [];
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
