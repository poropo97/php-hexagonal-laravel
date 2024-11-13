<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProductRepository;
use App\Domain\Entities\Product;

class CreateProduct
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Executes the use case to create a new product
     *
     * @param string $name
     * @param float $reservePrice
     * @param string $status
     * @return Product
     * @throws \InvalidArgumentException
     */
    public function execute(string $name, float $reservePrice, string $status = Product::STATUS_AVAILABLE): Product
    {
        // Validate the status
        if (!in_array($status, Product::getAllowedStatuses())) {
            throw new \InvalidArgumentException("The provided status is not valid.");
        }

        // Create an instance of Product
        $product = new Product(
            id: null, // Will be assigned when saved to the database
            name: $name,
            reservePrice: $reservePrice,
            status: $status
        );

        // Save the product in the repository
        $this->productRepository->save($product);

        return $product;
    }
}

