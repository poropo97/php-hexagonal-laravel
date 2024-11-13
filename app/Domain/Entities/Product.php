<?php

namespace App\Domain\Entities;

use App\Domain\Entities\Bid;

class Product
{
    private ?int $id;                  // Unique identifier for the product, nullable until persisted
    private string $name;              // Name of the product
    private float $reservePrice;       // Reserve price for the product
    private string $status;            // Current status of the product
    private ?\DateTime $expirationDate; // Expiration date for the auction, if any
    private ?Bid $winningBid = null;   // Winning bid for the auction, if one exists

    // Product status constants
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_SOLD = 'sold';
    public const STATUS_EXPIRED = 'expired';

    /**
     * Constructor to initialize a product.
     *
     * @param int|null $id Optional ID if the product is already persisted in the database
     * @param string $name Name of the product
     * @param float $reservePrice Minimum reserve price required for a successful sale
     * @param string $status Initial status of the product
     * @param \DateTime|null $expirationDate Optional expiration date for the auction
     */
    public function __construct(?int $id, string $name, float $reservePrice, string $status, ?\DateTime $expirationDate = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->reservePrice = $reservePrice;
        $this->setStatus($status); // Use the setter to apply validation
        $this->expirationDate = $expirationDate;
    }

    /**
     * Get all allowed statuses for the product.
     *
     * @return string[]
     */
    public static function getAllowedStatuses(): array
    {
        return [
            self::STATUS_AVAILABLE,
            self::STATUS_RESERVED,
            self::STATUS_SOLD,
            self::STATUS_EXPIRED,
        ];
    }

    /**
     * Get the product ID.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the name of the product.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the reserve price of the product.
     *
     * @return float
     */
    public function getReservePrice(): float
    {
        return $this->reservePrice;
    }

    /**
     * Get the current status of the product.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the status of the product, with validation.
     *
     * @param string $status
     * @return void
     * @throws \InvalidArgumentException if the status is not allowed
     */
    public function setStatus(string $status): void
    {
        if (!in_array($status, self::getAllowedStatuses(), true)) {
            throw new \InvalidArgumentException("Invalid status for the product.");
        }
        $this->status = $status;
    }

    /**
     * Get the expiration date of the auction, if set.
     *
     * @return \DateTime|null
     */
    public function getExpirationDate(): ?\DateTime
    {
        return $this->expirationDate;
    }

    /**
     * Set the expiration date for the auction.
     *
     * @param \DateTime|null $expirationDate
     * @return void
     */
    public function setExpirationDate(?\DateTime $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * Get the winning bid, if there is one.
     *
     * @return Bid|null
     */
    public function getWinningBid(): ?Bid
    {
        return $this->winningBid;
    }

    /**
     * Set the winning bid and update the status to sold.
     *
     * @param Bid $winningBid
     * @return void
     */
    public function setWinningBid(Bid $winningBid): void
    {
        $this->winningBid = $winningBid;
        $this->setStatus(self::STATUS_SOLD);
    }
}
