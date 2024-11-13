<?php

namespace App\Console\Commands;

use App\Application\UseCases\FinishBid;
use App\Domain\Repositories\ProductRepository;

use Illuminate\Console\Command;
use App\In;

class ListAvailableProducts extends Command
{
    protected $signature = 'auction:list-products';
    protected $description = 'List all available products for auction';

    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct();
        $this->productRepository = $productRepository;
    }

    public function handle()
    {
        $products = $this->productRepository->findAvailable();

        if (empty($products)) {
            $this->info('No products available for auction.');
            return;
        }

        $this->table(['ID', 'Name','Status','Reserve Price'], array_map(function ($product) {
            return [
                $product->getId(),
                $product->getName(),
                $product->getStatus(),
                $product->getReservePrice(),
            ];
        }, $products));
    }
}
