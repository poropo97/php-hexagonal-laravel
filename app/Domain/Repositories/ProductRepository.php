<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Product;

interface ProductRepository
{
    /**
     * Find a product by its ID
     *
     * @param int $id
     * @return Product|null
     */
    public function find(int $id): ?Product;

    /**
     * Save a product
     *
     * @param Product $product
     * @return void
     */
    public function save(Product $product): void;

    /**
     * Update the status of a product
     *
     * @param int $productId
     * @param string $status
     * @return void
     */
    public function updateStatus(int $productId, string $status): void;

    /**
     * Find all products
     *
     * @return Product[]
     */
    public function findAvailable(): array;
}
