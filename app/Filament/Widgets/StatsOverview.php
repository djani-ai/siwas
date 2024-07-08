<?php

namespace App\Filament\Widgets;

use App\Models\Lhp;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    // protected static string $color = 'info';
    protected static bool $isLazy = false;
    // protected int | string | array $columnSpan = 'full';


    protected function getStats(): array
    {
        $forma = Lhp::count();
        $user = Auth::user();
        $lhpCount = $user->lhps()->count();

        return [
            Stat::make('Total Form A', $forma)
                ->description('PKD Se-Kec. Brondong')
                ->color('success')
                ->chart([2, 3, 5, 10, 4, 17])
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Form A Kamu', $lhpCount)
                ->description('Form A Kamu Saja')
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([2, 3, 5, 10, 4, 17]),
        ];
    }
}
