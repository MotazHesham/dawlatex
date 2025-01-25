<?php

namespace App\Services;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\ProductStock;
use App\Utility\ProductUtility;

class ProductStockService
{
    public function store(array $data, $product)
    {
        $collection = collect($data);

        $options = ProductUtility::get_attribute_options($collection);
        
        //Generates the combinations of customer choice options
        $combinations = (new CombinationService())->generate_combination($options);
        
        $variant = '';
        if (count($combinations) > 0) {
            // Step 1: Collect all updated variants
            $updatedVariants = [];

            $product->variant_product = 1;
            $product->save();
            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);

                // Store the items that updated
                $updatedVariants[] = str_replace('.', '_', $str); 
                // Prepare the data array for updateOrCreate
                $data = [
                    'price' => request()['price_' . str_replace('.', '_', $str)],
                    'sku' => request()['sku_' . str_replace('.', '_', $str)],
                    'qty' => request()['qty_' . str_replace('.', '_', $str)],
                    'image' => request()['img_' . str_replace('.', '_', $str)],
                ]; 
                if(isset($collection['purchase_price'])){
                    $data['price'] = request()['price_' . str_replace('.', '_', $str)];
                    $data['purchase_price'] = request()['price_' . str_replace('.', '_', $str)];
                }else{
                    $data['price'] = request()['price_' . str_replace('.', '_', $str)];
                    $data['purchase_price'] = request()['purchase_price_' . str_replace('.', '_', $str)];
                } 
                ProductStock::updateOrCreate(
                    ['product_id' => $product->id, 'variant' => $str],
                    $data
                );
            }
            // Step 2: Remove items that are not part of the updated variants
            ProductStock::where('product_id', $product->id)
                ->whereNotIn('variant', $updatedVariants)
                ->delete();
        } else {
            unset($collection['colors_active'], $collection['colors'], $collection['choice_no']);
            $qty = $collection['current_stock'];
            $price = $product->unit_price; 
            $purchase_price = $product->purchase_price; 
            unset($collection['current_stock']);

            $data = [
                'sku' => $collection['sku'],
                'variant' => $variant,
                'qty' => $qty,
                'price' => $price, 
                'purchase_price' => $purchase_price, 
            ]; 
            ProductStock::updateOrCreate(
                ['product_id' => $product->id],
                $data
            );
        }
    }

    public function product_duplicate_store($product_stocks , $product_new)
    {
        foreach ($product_stocks as $key => $stock) {
            $product_stock                  = new ProductStock;
            $product_stock->product_id      = $product_new->id;
            $product_stock->variant         = $stock->variant;
            $product_stock->price           = $stock->price;
            $product_stock->purchase_price  = $stock->purchase_price;
            $product_stock->sku             = $stock->sku;
            $product_stock->qty             = $stock->qty;
            $product_stock->save();
        }
    }
}
