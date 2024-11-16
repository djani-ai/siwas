<?php

namespace App\Filament\Widgets;

use App\Models\Lhp;
use App\Models\User;
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
        $users = User::count();
        $lhpCount = $user->lhps->count();
        $role = auth()->user()->roles->pluck('id');
        if ((!$role->contains(4))) {
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
                Stat::make('Total Pengguna', $users)
                    ->description('Total Pengguna Se-Kec. Brondong')
                    ->color('success')
                    ->chart([2, 3, 5, 10, 4, 17])
                    ->descriptionIcon('heroicon-m-arrow-trending-up'),
            ];
        } else {
            return [
                Stat::make('Selamat Datang', $user->name)
                    ->description($user->kel->name . ' - ' . $user->tps->name)
                    ->color('success'),
                Stat::make('Total Pengguna', $users)
                    ->description('Total Pengguna Se-Kec. Brondong')
                    ->color('success')
                    ->chart([2, 3, 5, 10, 4, 17])
                    ->descriptionIcon('heroicon-m-arrow-trending-up'),
            ];
        }
    }
}
