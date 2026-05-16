<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Product;
use Illuminate\Support\Facades\DB;


class InventoryStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total Produk', Product::count())
                ->description('Jenis produk yang terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
            Stat::make('Total Nilai Aset', 'Rp ' . number_format(Product::sum(DB::raw('stock * price')), 0, ',', '.'))
                ->description('Estimasi nilai uang dalam gudang')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Stock Menipis', Product::whereRaw('stock <= min_stock')->count())
                ->description('Produk dengan stock menipis')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color(fn($state) => $state > 0 ? 'danger' : 'success'),
        ];
    }
}
