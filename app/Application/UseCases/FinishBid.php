<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProductRepository;
use App\Domain\Repositories\BidRepository;
use App\Domain\Services\AuctionService;
use App\Domain\Entities\Product;

class FinishBid
{
    private ProductRepository $productRepository;
    private BidRepository $bidRepository;
    private AuctionService $auctionService;

    public function __construct(ProductRepository $productRepository, BidRepository $bidRepository, AuctionService $auctionService)
    {
        $this->productRepository = $productRepository;
        $this->bidRepository = $bidRepository;
        $this->auctionService = $auctionService;
    }

    public function execute(int $productId): array
    {
        // Get the product
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new \Exception("Product not found.");
        }

        // Get all bids for the product
        $bids = $this->bidRepository->findByProduct($product);

        if (empty($bids)) {
            throw new \Exception("No bids for this product.");
        }

        // Determine the
        $auctionResult = $this->auctionService->determineWinner($bids, $product);
        $winningBid = $auctionResult['winner'];
        $winningPrice = $auctionResult['winningPrice'];

        // Asignar la puja ganadora y el estado al producto
        $product->setWinningBid($winningBid);

        // Guardar el producto actualizado en el repositorio
        $this->productRepository->save($product);

        return [
            'product' => $product,
            'winningBid' => $winningBid,
            'winningPrice' => $winningPrice,
        ];
    }
}
