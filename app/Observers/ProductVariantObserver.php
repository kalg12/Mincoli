<?php

namespace App\Observers;

use App\Models\ProductVariant;

class ProductVariantObserver
{
    /**
     * Handle the ProductVariant "created" event.
     */
    public function created(ProductVariant $productVariant): void
    {
        $this->updateProductStock($productVariant);
    }

    /**
     * Handle the ProductVariant "updated" event.
     */
    public function updated(ProductVariant $productVariant): void
    {
        $this->updateProductStock($productVariant);
    }

    /**
     * Handle the ProductVariant "deleted" event.
     */
    public function deleted(ProductVariant $productVariant): void
    {
        $this->updateProductStock($productVariant);
    }

    /**
     * Handle the ProductVariant "restored" event.
     */
    public function restored(ProductVariant $productVariant): void
    {
        $this->updateProductStock($productVariant);
    }

    /**
     * Handle the ProductVariant "force deleted" event.
     */
    public function forceDeleted(ProductVariant $productVariant): void
    {
        $this->updateProductStock($productVariant);
    }

    /**
     * Update the parent product's stock based on the sum of all its variants.
     */
    protected function updateProductStock(ProductVariant $variant): void
    {
        $product = $variant->product;
        
        if ($product) {
            // Recalculate total stock from all variants (excluding deleted ones unless we are restoring)
            // Note: If we are in a deleted event, the variant is already soft-deleted so it won't be counted by default relations
            // If we are force deleting, it's gone.
            
            // We use the relation 'variants' which by default excludes soft deleted items
            $totalStock = $product->variants()->sum('stock');
            
            // We update the product stock without triggering events to avoid infinite loops if product has observers too
            // (though product observers usually check other things)
            $product->updateQuietly(['stock' => $totalStock]);
        }
    }
}
