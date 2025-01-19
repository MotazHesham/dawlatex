<?php

namespace App\Console\Commands;

use App\Models\Product;
use Artisan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProductPublishDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:product-publish-days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Un Published products that has passed the publish_days for the product';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::where('published', 1)
                            ->whereNotNull('published_at')
                            ->get();
        foreach ($products as $product) {
            if ($product->publish_days > 0) {
                $daysPublished = $product->published_at->diffInDays(Carbon::now());
                if ($daysPublished >= $product->publish_days) {
                    $product->update(['published' => 0, 'approved' => 0]); // Use update for cleaner syntax
                    $this->info("Product with id {$product->id} has been unpublished");
                }
            }
        } 
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
    }
}
