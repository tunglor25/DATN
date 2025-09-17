<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupVariants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'variants:cleanup {--product-id= : Clean specific product}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate variants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productId = $this->option('product-id');
        
        if ($productId) {
            $product = \App\Models\Product::find($productId);
            if (!$product) {
                $this->error("Product not found");
                return 1;
            }
            $this->cleanupProduct($product);
        } else {
            $this->cleanupAll();
        }
        
        return 0;
    }
    
    private function cleanupAll()
    {
        $this->info('Cleaning all products...');
        $products = \App\Models\Product::with('variants.attributeValues')->get();
        $total = 0;
        
        foreach ($products as $product) {
            $removed = $this->cleanupProduct($product, false);
            $total += $removed;
        }
        
        $this->info("Total removed: {$total}");
    }
    
    private function cleanupProduct($product, $showInfo = true)
    {
        if ($showInfo) {
            $this->info("Cleaning product: {$product->name}");
        }
        
        $variants = $product->variants()->with('attributeValues')->get();
        $duplicates = [];
        $removed = 0;
        
        // Group by attribute combination
        foreach ($variants as $variant) {
            $key = $variant->attributeValues->pluck('id')->sort()->implode(',');
            if (!isset($duplicates[$key])) {
                $duplicates[$key] = [];
            }
            $duplicates[$key][] = $variant;
        }
        
        // Remove duplicates
        foreach ($duplicates as $key => $group) {
            if (count($group) > 1) {
                if ($showInfo) {
                    $this->warn("Found " . count($group) . " duplicates");
                }
                
                // Keep first, remove others
                array_shift($group);
                foreach ($group as $variant) {
                    if ($variant->image) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($variant->image);
                    }
                    $variant->attributeValues()->detach();
                    $variant->delete();
                    $removed++;
                }
            }
        }
        
        if ($showInfo && $removed > 0) {
            $this->info("Removed {$removed} duplicates");
        }
        
        return $removed;
    }
}
