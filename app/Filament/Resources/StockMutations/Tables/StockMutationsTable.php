<?php

namespace App\Filament\Resources\StockMutations\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

// PERBAIKAN IMPORT DISINI:
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ExportAction;      // Harus dari Tables\Actions
use Filament\Actions\ExportBulkAction;  // Harus dari Tables\Actions
class StockMutationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Jumlah (Qty)')
                    ->sortable(),
                TextColumn::make('reference')
                    ->label('Referensi / Catatan')
                    ->searchable(),

            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(\App\Filament\Exports\StockMutationExporter::class)
                    ->label('Unduh Laporan')
                    ->columnMapping(false)
                    ->icon('heroicon-m-arrow-down-tray'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(\App\Filament\Exports\StockMutationExporter::class)
                        ->label('Eksport Data Terpilih')
                        ->icon('heroicon-m-arrow-down-tray'),
                ]),

            ])
            ->filters([
                //
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('dari_tanggal')->label('Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['dari_tanggal'], fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['sampai_tanggal'], fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data) {
                        $indicators = [];
                        if ($data['dari_tanggal'] ?? null) {
                            $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['dari_tanggal'])->format('d M Y');
                        }
                        if ($data['sampai_tanggal'] ?? null) {
                            $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['sampai_tanggal'])->format('d M Y');
                        }
                        return $indicators;
                    })

            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
