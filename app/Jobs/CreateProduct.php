<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\OdooService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */ 
    
    public function __construct(public Product $product)
    { 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $odooService = app(OdooService::class);

        $model = 'product.template';
        $data = [
            'name' => $this->product->name,
            'list_price' => $this->product->unit_price
        ];
        
        $newRecordId = $odooService->create($model, $data);
        $this->product->odoo_ref_id = $newRecordId;
        $this->product->save();
    }
}
