<?php

namespace Tests\Unit\Domain\Services;

use PHPUnit\Framework\TestCase;
use App\Domain\Services\AuctionService;
use App\Domain\Entities\Bid;
use App\Domain\Entities\Product;
use App\Domain\Entities\User;

class AuctionServiceTest extends TestCase
{
    private AuctionService $auctionService;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auctionService = new AuctionService();
        
        // Crear un producto con un precio de reserva de 100
        $this->product = new Product(id: 6, name: "Product TEST", reservePrice: 100, status: Product::STATUS_AVAILABLE);
    }

    public function testSingleBidMeetingReservePrice()
    {
        // Caso: Una sola puja cumple el precio de reserva
        $user = new User(1, "Test User");
        $bid = new Bid($user, $this->product, 120, 1); // Asignar ID 1

        $result = $this->auctionService->determineWinner([$bid], $this->product);

        $this->assertEquals($bid, $result['winner']);
        $this->assertEquals(100, $result['winningPrice']); // Precio de reserva
    }

    public function testMultipleBidsMeetingReservePrice()
    {
        // Caso: Múltiples pujas cumplen el precio de reserva
        $user1 = new User(1, "User 1");
        $user2 = new User(2, "User 2");

        $bids = [
            new Bid($user1, $this->product, 110, 1), // ID 1
            new Bid($user2, $this->product, 130, 2), // ID 2
            new Bid($user1, $this->product, 120, 3)  // ID 3
        ];

        $result = $this->auctionService->determineWinner($bids, $this->product);

        $this->assertEquals($bids[1], $result['winner']); // Ganador: puja más alta de 130 (ID 2)
        $this->assertEquals(120, $result['winningPrice']); // Precio de venta: segunda puja más alta de otro usuario
    }

    public function testNoBidsMeetReservePrice()
    {
        // Caso: Ninguna puja cumple el precio de reserva
        $user = new User(1, "Test User");
        $bids = [
            new Bid($user, $this->product, 80, 1),  // ID 1 - Menor que el precio de reserva
            new Bid($user, $this->product, 90, 2)   // ID 2 - Menor que el precio de reserva
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No bids meet the reserve price.");

        $this->auctionService->determineWinner($bids, $this->product);
    }

    public function testTieAtHighestBid()
    {
        // Caso: Empate en las pujas más altas
        $user1 = new User(1, "User 1");
        $user2 = new User(2, "User 2");

        $bids = [
            new Bid($user1, $this->product, 130, 1), // ID 1
            new Bid($user2, $this->product, 130, 2), // ID 2 - Empate con el bid de user1
            new Bid($user1, $this->product, 125, 3)  // ID 3
        ];

        $result = $this->auctionService->determineWinner($bids, $this->product);

        $this->assertEquals($bids[0], $result['winner']); // Ganador: primer bid de 130 (ID 1)
        $this->assertEquals(130, $result['winningPrice']); // Precio de venta: segunda puja más alta de otro usuario (ID 2)
    }

    public function testWinningPriceFromDifferentUser()
    {
        // Caso adicional: El usuario ganador tiene múltiples pujas, y el precio de venta debe provenir de otro usuario
        $user1 = new User(1, "User 1");
        $user2 = new User(2, "User 2");
        $user3 = new User(3, "User 3");

        $bids = [
            new Bid($user1, $this->product, 450, 1), // ID 1
            new Bid($user2, $this->product, 480, 2), // ID 2
            new Bid($user2, $this->product, 500, 3), // ID 3 - Ganador
            new Bid($user3, $this->product, 420, 4)  // ID 4
        ];

        $result = $this->auctionService->determineWinner($bids, $this->product);

        $this->assertEquals($bids[2], $result['winner']); // Ganador: puja de 500 (ID 3)
        $this->assertEquals(450, $result['winningPrice']); // Precio de venta: puja más alta de otro usuario (450)
    }
}
