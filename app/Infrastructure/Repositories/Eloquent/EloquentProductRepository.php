<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Entities\Product;
use App\Domain\Repositories\ProductRepository;
use App\Models\Product as ProductModel; // <- 🦉 better to call it ProductModel to avoid confusion with the Product entity

class EloquentProductRepository implements ProductRepository
{
    public function find(int $id): ?Product
    {
        $productModel = ProductModel::find($id);

        if (!$productModel) {
            return null;
        }


        return new Product(
            id: $productModel->id,
            name: $productModel->name,
            reservePrice: $productModel->reserve_price,
            status: $productModel->status
        );
    }

    /*
    * Save a product
    *
    * @param Product $product
    * @return void
    */
    public function save(Product $product): void
    {
        //  if not exists, create one
        if(!$productModel = ProductModel::find($product->getId())) {
            $productModel = new ProductModel();
        } 
        // or.. 
        $productModel = ProductModel::find($product->getId()) ?? new ProductModel();
        // save the data
        $productModel->name = $product->getName();
        $productModel->reserve_price = $product->getReservePrice();
        $productModel->status = $product->getStatus();
        // expires_at now+5 days
        $productModel->expires_at = now()->addDays(5);
        $productModel->save();
    }

    /* specific method to update the status of a product
    *
    * @param int $productId
    * @param string $status
    * @return void
    */
    public function updateStatus(int $productId, string $status): void
    {

        // 🧐 the verification of the status i think should be done in the entity...
        
        
        // find the product
        if ($productModel = ProductModel::find($productId)) {
            $productModel->status = $status;
            $productModel->save();
        }
    }

    /* Get available products
    *
    * @return Product[]
    */
    public function findAvailable(): array
    {
        $productModels = ProductModel::where('status', Product::STATUS_AVAILABLE)->get();

        return $productModels->map(function ($productModel) {
            return new Product(
                id: $productModel->id,
                name: $productModel->name,
                reservePrice: $productModel->reserve_price,
                status: $productModel->status
            );
        })->all();
    }


}
