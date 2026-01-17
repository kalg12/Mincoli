<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:sync-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizes product stock with the sum of its variants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting stock synchronization...');

        $products = Product::whereHas('variants')->get();
        $bar = $this->output->createProgressBar(count($products));

        $count = 0;
        
        foreach ($products as $product) {
            $variantStock = $product->variants()->sum('stock');
            
            if ($product->stock != $variantStock) {
                $product->updateQuietly(['stock' => $variantStock]);
                $count++;
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        
        $this->info("Synchronization complete. Updated {$count} products.");
    }
}
