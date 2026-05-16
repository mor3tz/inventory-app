<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\StockMutation;
use Filament\Notifications\Notification;

class StockMutationObserver
{
    /**
     * Handle the StockMutation "created" event.
     */
    public function created(StockMutation $stockMutation): void
    {
        // Take the current stock of the product
        $product = $stockMutation->product;
        $this->adjustStock($product, $stockMutation->type,$stockMutation->quantity);
    }

    /**
     * Handle the StockMutation "updated" event.
     */
    public function updated(StockMutation $stockMutation): void
    {
        $oldProduct = $stockMutation->getOriginal('product_id') ? Product::find($stockMutation->getOriginal('product_id')) : null;
        $oldQty = $stockMutation->getOriginal('quantity');
        $oldType = $stockMutation->getOriginal('type');

        if ($oldProduct) {
            $reverseType = ($oldType === 'in') ? 'out' : 'in';
            $this->adjustStock($oldProduct, $reverseType, $oldQty);
        }

        $newProduct = $stockMutation->product;
        if ($newProduct) {
            $this->adjustStock($newProduct, $stockMutation->type, $stockMutation->quantity);
        }
    }

    /**
     * Handle the StockMutation "deleted" event.
     */
    public function deleted(StockMutation $stockMutation): void
    {
        //
        $product = $stockMutation->product;

        if (!$product) return;
        

        if ($stockMutation->type === 'in') {
            $product->decrement('stock', $stockMutation->quantity);
        } elseif ($stockMutation->type === 'out') {
            $product->increment('stock', $stockMutation->quantity);
        }
    }

    private function adjustStock($product, $type, $quantity): void
    {
        if ($type === 'in') {
            $product->increment('stock', $quantity);
        } else {
            $product->decrement('stock', $quantity);
        }
    }

    protected function checkLowStock($product)
    {
        if ($product->stock < 10) {
            Notification::make()
                ->title('Stok Produk ' . $product->name . ' Rendah')
                ->body('Stok produk ' . $product->name . ' saat ini hanya ' . $product->stock . '. Segera lakukan restock!')
                ->danger()
                ->send();
        }
    }
}
