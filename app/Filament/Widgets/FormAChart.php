<?php

namespace App\Filament\Widgets;

use App\Models\Kel;
use App\Models\Lhp;
use App\Models\Tps;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FormAChart extends ChartWidget
{
    // protected static ?string $heading = 'Jumlah Form A Semua Desa';
    protected static string $color = 'info';
    protected static bool $isLazy = false;
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '400px';



    protected function getData(): array
    {
        $kelNames = Kel::pluck('name')->toArray();
        $kels = Kel::withCount('lhps')->pluck('lhps_count')->toArray();
        $tps = Kel::withCount('tps')->pluck('tps_count')->toArray();
        $role = auth()->user()->roles->pluck('id');
        if ((!$role->contains(4))) {
            return [
                'datasets' => [
                    [
                        'label' => 'Jumlah Form A Per Desa',
                        'data' => $kels,
                        'backgroundColor' => '#36A2EB',
                        'borderColor' => '#9BD0F5',
                    ],
                ],
                'labels' => $kelNames,
            ];
        } else {
            return [
                'datasets' => [
                    [
                        'label' => 'Jumlah TPS',
                        'data' => $tps,
                        'backgroundColor' => '#36A2EB',
                        'borderColor' => '#9BD0F5',
                    ],
                ],
                'labels' => $kelNames,
            ];
        }
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
