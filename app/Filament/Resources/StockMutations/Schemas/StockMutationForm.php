<?php

namespace App\Filament\Resources\StockMutations\Schemas;

use Filament\Schemas\Schema;
use App\Models\Product;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class StockMutationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->helperText(function ($get) {
                        $product = Product::find($get('product_id'));
                        if ($product) {
                            return 'Stok saat ini: ' . $product->stock;
                        }
                    })
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($set) => $set('quantity', null)),

                Select::make('type')
                    ->label('Jenis Mutasi')
                    ->options([
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                        'adjustment' => 'Penyesuaian (Opname)',
                    ])
                    ->required(),

                TextInput::make('quantity')
                    ->label('Jumlah (Qty)')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->rules([
                        fn ($get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            if ($get('type') === 'out') {
                                $productId = $get('product_id');
                                $product = Product::find($productId);

                                if (! $product) {
                                    return;
                                }

                                if ($value > $product->stock) {
                                    $fail('Jumlah keluar tidak boleh melebihi stok yang tersedia (' . $product->stock . ').');
                                }
                            }
                        } 
                    ]),

                TextInput::make('reference')
                    ->label('Referensi / Catatan')
                    ->placeholder('Contoh: No. Invoice PO-001, atau Barang Rusak')
                    ->maxLength(255),
            ]);
    }
}
