<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use App\Models\Participant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengumuman', Announcement::count())
                ->description('Seluruh pengumuman di sistem')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Pengumuman Aktif', Announcement::where('status', 'published')->count())
                ->description('Pengumuman yang sedang tayang')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('success'),

            Stat::make('Total Peserta', Participant::count())
                ->description('Seluruh peserta terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
        ];
    }
}
