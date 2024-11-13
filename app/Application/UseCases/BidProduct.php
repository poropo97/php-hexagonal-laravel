<?php
namespace App\Application\UseCases;

use App\Domain\Repositories\ProductRepository;
use App\Domain\Repositories\BidRepository;
use App\Domain\Entities\Bid;
use App\Domain\Entities\User;

class BidProduct
{
    private ProductRepository $productRepository;
    private BidRepository $bidRepository;

    public function __construct(ProductRepository $productRepository, BidRepository $bidRepository)
    {
        $this->productRepository = $productRepository;
        $this->bidRepository = $bidRepository;
    }

    public function execute(User $user, int $productId, float $bidAmount): void
    {
        // find the product
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new \Exception("Producto no encontrado.");
        }

        // Aquí podrías añadir reglas de validación, como si la puja es superior al precio de reserva
        $bid = new Bid($user, $product, $bidAmount);
        $this->bidRepository->save($bid);
    }
}
