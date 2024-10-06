<?php

namespace App\Filament\Resources\LhpResource\Pages;

use App\Filament\Resources\LhpResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLhp extends ViewRecord
{
    protected static string $resource = LhpResource::class;
    protected static string $view = 'filament.resources.viewlhp';
}
