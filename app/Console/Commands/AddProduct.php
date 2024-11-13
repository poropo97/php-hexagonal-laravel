<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Application\UseCases\CreateProduct;

class AddProduct extends Command
{
    protected $signature = 'product:create {name} {reservePrice} {status?}';
    protected $description = 'Create a new product';

    private CreateProduct $createProduct;

    public function __construct(CreateProduct $createProduct)
    {
        parent::__construct();
        $this->createProduct = $createProduct;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $reservePrice = (float)$this->argument('reservePrice');
        $status = $this->argument('status') ?? 'available';

        try {
            $product = $this->createProduct->execute($name, $reservePrice, $status);
            $this->info("Product created: ID {$product->getId()}, Name: {$product->getName()}, Reserve Price: {$product->getReservePrice()}, Status: {$product->getStatus()}");
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());
        }
    }
}

