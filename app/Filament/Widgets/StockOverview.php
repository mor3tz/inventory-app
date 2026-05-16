<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\StockMutation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            //
            Stat::make('total Stok Barang', Product::sum('stock'))
                ->description('Total stok barang dalam gudang')
                ->descriptionIcon('heroicon-m-cube-transparent')
                ->color('success'),
            Stat::make('Masuk (hari ini)', StockMutation::where('type', 'in')
                ->whereDate('created_at', now()->format('Y-m-d'))
                ->sum('quantity'))
                ->description('Total barang diterima')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Keluar (hari ini)', StockMutation::where('type', 'out')
                ->whereDate('created_at', now()->format('Y-m-d'))
                ->sum('quantity'))
                ->description('Total barang keluar')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
        ];
    }
}
