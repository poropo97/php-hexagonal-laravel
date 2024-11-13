<?php

namespace App\Domain\Services;

use App\Domain\Entities\Bid;
use App\Domain\Entities\Product;

class AuctionService
{
    /**
     * Determines the winner and the winning price for a second-price, sealed-bid auction.
     *
     * @param Bid[] $bids List of bids for the product.
     * @param Product $product The product being auctioned.
     * @return array Contains 'winner' (the winning Bid) and 'winningPrice' (the sale price).
     * @throws \Exception If no bids meet the reserve price.
     */
    public function determineWinner(array $bids, Product $product): array
    {
        $reservePrice = $product->getReservePrice();

        // Paso 1: Filtrar las pujas que cumplen o exceden el precio de reserva
        $eligibleBids = array_filter($bids, fn(Bid $bid) => $bid->getAmount() >= $reservePrice);

        if (empty($eligibleBids)) {
            throw new \Exception("No bids meet the reserve price.");
        }

        // Paso 2: Ordenar las pujas elegibles en orden descendente de monto, luego ascendente de ID
        usort($eligibleBids, function (Bid $a, Bid $b) {
            if ($b->getAmount() === $a->getAmount()) {
                // Criterio de desempate: puja con ID menor gana (puja más antigua)
                return $a->getId() <=> $b->getId();
            }
            return $b->getAmount() <=> $a->getAmount();
        });

        // Paso 3: Identificar la puja más alta como la puja ganadora
        $winningBid = $eligibleBids[0];

        // Paso 4: Determinar el precio de venta excluyendo todas las pujas del ganador
        $winningPrice = $reservePrice;

        foreach ($eligibleBids as $bid) {
            if ($bid->getUser()->getId() !== $winningBid->getUser()->getId()) {
                $winningPrice = $bid->getAmount();
                break;
            }
        }

        return [
            'winner' => $winningBid,
            'winningPrice' => $winningPrice,
        ];
    }
}
