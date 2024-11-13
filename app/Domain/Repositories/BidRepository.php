<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Product;
use App\Domain\Entities\Bid;

interface BidRepository
{
    /**
     * Find a bid by its ID
     *
     * @param int $id
     * @return Bid|null
     */
    public function find(int $id): ?Bid;

    /**
     * Save a bid
     *
     * @param Bid $bid
     * @return void
     */
    public function save(Bid $bid): void;

    /**
     * Find bids by product
     *
     * @param Product $product
     * @return Bid[]
     */
    public function findByProduct(Product $product): array;
}
