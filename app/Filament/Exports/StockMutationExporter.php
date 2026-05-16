<?php

namespace App\Filament\Exports;

use App\Models\StockMutation;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class StockMutationExporter extends Exporter
{
    protected static ?string $model = StockMutation::class;

    public static function getColumns(): array
    {
        return [
            //
            ExportColumn::make('created_at')
                ->label('Tanggal')
                ->formatStateUsing(fn($state) => $state ? $state->format('Y-m-d H:i:s') : null),
            ExportColumn::make('product.name')
                ->label('Nama Produk'),
            ExportColumn::make('type')
                ->label('Jenis')
                ->formatStateUsing(fn($state) => $state === 'in' ? 'Masuk' : 'Keluar'),
            ExportColumn::make('quantity')
                ->label('Jumlah (Qty)'),
            ExportColumn::make('reference')
                ->label('Referensi / Catatan'),
        ];
    }


    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your stock mutation export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
