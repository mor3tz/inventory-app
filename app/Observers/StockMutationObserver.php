<?php

namespace App\Observers;

use App\Models\StockMutation;

class StockMutationObserver
{
    /**
     * Handle the StockMutation "created" event.
     */
    public function created(StockMutation $stockMutation): void
    {
        // Take the current stock of the product
        $product = $stockMutation->product;
        $product->stock += $stockMutation->quantity;
        $product->save();
    }

    /**
     * Handle the StockMutation "updated" event.
     */
    public function updated(StockMutation $stockMutation): void
    {
        //
    }

    /**
     * Handle the StockMutation "deleted" event.
     */
    public function deleted(StockMutation $stockMutation): void
    {
        //
    }

    /**
     * Handle the StockMutation "restored" event.
     */
    public function restored(StockMutation $stockMutation): void
    {
        //
    }

    /**
     * Handle the StockMutation "force deleted" event.
     */
    public function forceDeleted(StockMutation $stockMutation): void
    {
        //
    }
}
