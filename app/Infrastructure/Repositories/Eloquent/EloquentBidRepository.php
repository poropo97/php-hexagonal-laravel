<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Entities\Bid;
use App\Domain\Entities\Product;
use App\Domain\Repositories\BidRepository;
use App\Models\Bid as BidModel;

class EloquentBidRepository implements BidRepository
{
    /**
     * Finds a bid by its ID
     *
     * @param int $id
     * @return Bid|null
     */
    public function find(int $id): ?Bid
    {
        $bidModel = BidModel::find($id);

        if (!$bidModel) {
            return null;
        }

        return $this->mapToEntity($bidModel);
    }

    /**
     * Saves a bid
     *
     * @param Bid $bid
     * @return void
     */
    public function save(Bid $bid): void
    {
        $bidModel = new BidModel();
        $bidModel->user_id = $bid->getUser()->getId();
        $bidModel->product_id = $bid->getProduct()->getId();
        $bidModel->amount = $bid->getAmount();
        $bidModel->save();
    }

    /**
     * Finds all bids associated with a specific product
     *
     * @param Product $product
     * @return Bid[]
     */
    public function findByProduct(Product $product): array
    {
        // Use `with` to load the `user` and `product` relationships when retrieving bids
        $bidModels = BidModel::with(['user', 'product'])
                    ->where('product_id', $product->getId())
                    ->get();

        return $bidModels->map(function ($bidModel) {
            return $this->mapToEntity($bidModel);
        })->toArray();
    }

    /**
     * Maps an Eloquent model to the domain entity Bid
     *
     * @param BidModel $bidModel
     * @return Bid
     */
    private function mapToEntity(BidModel $bidModel): Bid
    {
        // Verify the user exists and map it to the domain User entity
        if (!$bidModel->user) {
            throw new \Exception("User not found for bid ID: {$bidModel->id}");
        }

        $user = new \App\Domain\Entities\User(
            $bidModel->user->id,
            $bidModel->user->name
        );

        // Map the product entity similarly
        $product = new \App\Domain\Entities\Product(
            $bidModel->product->id,
            $bidModel->product->name,
            $bidModel->product->reserve_price,
            $bidModel->product->status
        );

        // Return the mapped Bid entity
        return new Bid(
            $user,
            $product,
            $bidModel->amount,
            $bidModel->id
        );
    }

}

