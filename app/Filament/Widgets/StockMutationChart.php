<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\StockMutation;


class StockMutationChart extends ChartWidget
{
    protected ?string $heading = 'Tren Mutasi Stok (7 Hari Terakhir)';
    protected static ?int $sort = 2;
    // protected ?string $maxHeight = '300px';
    protected function getData(): array
    {
        $dataMasuk = [];
        $dataKeluar = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');

            $dataMasuk[] = StockMutation::whereDate('created_at', $date)
                ->where('type', 'in')
                ->sum('quantity');

            $dataKeluar[] = StockMutation::whereDate('created_at', $date)
                ->where('type', 'out')
                ->sum('quantity');
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Masuk',
                    'data' => $dataMasuk,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                ],
                [
                    'label' => 'Keluar',
                    'data' => $dataKeluar,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                ],
            ],

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
