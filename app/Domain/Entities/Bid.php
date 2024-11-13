<?php

namespace App\Domain\Entities;

use App\Domain\Entities\Product;
use App\Domain\Entities\User;

class Bid
{
    private ?int $id;        // Unique identifier for the bid, nullable until persisted
    private float $amount;   // Amount of the bid
    private User $user;      // User who placed the bid
    private Product $product; // Product associated with the bid

    /**
     * Constructor to initialize a bid.
     *
     * @param User $user The user who placed the bid
     * @param Product $product The product being bid on
     * @param float $amount The bid amount
     * @param int|null $id Optional ID, if already persisted in the database
     */
    public function __construct(User $user, Product $product, float $amount, ?int $id = null)
    {
        $this->id = $id;
        $this->user = $user;
        $this->product = $product;
        $this->setAmount($amount); // Use the setter to apply validation
    }

    /**
     * Get the ID of the bid.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the user who placed the bid.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Get the product associated with the bid.
     *
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * Get the amount of the bid.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Set the amount of the bid.
     *
     * @param float $amount
     * @return void
     * @throws \InvalidArgumentException if the amount is not greater than zero
     */
    public function setAmount(float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("The bid amount must be greater than zero.");
        }
        $this->amount = $amount;
    }
}
