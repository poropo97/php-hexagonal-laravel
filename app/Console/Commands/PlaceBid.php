<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\Repositories\ProductRepository;
use App\Domain\Repositories\BidRepository;
use App\Domain\Entities\Bid;
use App\Domain\Entities\User;

class PlaceBid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auction:place-bid {userId} {productId} {amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Place a bid on a product by a user';

    private ProductRepository $productRepository;
    private BidRepository $bidRepository;

    /**
     * Constructor to inject dependencies.
     */
    public function __construct(ProductRepository $productRepository, BidRepository $bidRepository)
    {
        parent::__construct();
        $this->productRepository = $productRepository;
        $this->bidRepository = $bidRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = (int) $this->argument('userId');
        $productId = (int) $this->argument('productId');
        $amount = (float) $this->argument('amount');

        // Validate bid amount
        if ($amount <= 0) {
            $this->error("The bid amount must be greater than zero.");
            return;
        }

        // Fetch the product
        $product = $this->productRepository->find($productId);
        if (!$product) {
            $this->error("Product with ID $productId not found.");
            return;
        }

        // Create a user instance (this example assumes a simple user entity)
        $user = new User($userId, "User {$userId}"); // Placeholder user instance

        // Create the bid and save it
        $bid = new Bid($user, $product, $amount);
        $this->bidRepository->save($bid);

        $this->info("Bid of {$amount} placed by user {$userId} on product {$product->getName()}.");
    }
}
