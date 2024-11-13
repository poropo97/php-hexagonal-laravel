# Auction System - Second-Price Sealed-Bid Auction

**Author**: Alejandro Ruesga Ortuño

## Overview

This project implements a second-price, sealed-bid auction system using PHP and Laravel. The system allows multiple buyers to place bids on a product with a reserve price, determines the winner based on the highest bid meeting the reserve price, and sets the winning price according to the second-highest bid above the reserve price.

## Table of Contents

- [Requirements](#requirements)
- [Setup Instructions](#setup-instructions)
- [Running the Application](#running-the-application)
  - [Available Commands](#available-commands)
- [Running Tests](#running-tests)
- [Code Structure and Explanation](#code-structure-and-explanation)
  - [Domain Layer](#domain-layer)
  - [Application Layer](#application-layer)
  - [Infrastructure Layer](#infrastructure-layer)
- [Author](#author)
- [Problem Statement](#problem-statement)

## Requirements

- PHP >= 8.0
- Composer
- Laravel Framework
- SQLite (for simplicity in this example)
- Git (to clone the repository)

## Setup Instructions

1. **Clone the Repository**

   ```bash
   git clone git@github.com:poropo97/php-hexagonal-laravel.git
   cd php-hexagonal-laravel
   ```

2. **Install Dependencies**

   ```bash
   composer install
   ```

3. **Set Up Environment Variables**

   Copy the example environment file and generate an application key.

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure the Database**

   For simplicity, we'll use SQLite. Create a new SQLite database file.

   ```bash
   touch database/database.sqlite
   ```

   Update the `.env` file to use SQLite:

   ```env
   DB_CONNECTION=sqlite
   ```

5. **Run Migrations**

   ```bash
   php artisan migrate
   ```



## Running the Application

The application uses Artisan commands to interact with the auction system.

### Available Commands

1. **List Available Products**

   Lists all products available for auction.

   ```bash
   php artisan auction:list-products
   ```

2. **Create a New Product**

   Creates a new product with a reserve price.

   ```bash
   php artisan product:create {name} {reservePrice} {status?}

   # Example:
   php artisan product:create "Vintage Watch" 100 "available"
   ```

3. **Place a Bid on a Product**

   Places a bid by a user on a specific product.

   ```bash
   php artisan auction:place-bid {userId} {productId} {amount}

   # Example:
   php artisan auction:place-bid 1 1 150
   ```

   **Note**: In this example, users are not stored in the database. User IDs are used directly for simplicity.

4. **Finalize an Auction**

   Finalizes the auction for a product, determines the winner, and sets the winning price.

   ```bash
   php artisan auction:finish {productId}

   # Example:
   php artisan auction:finish 1
   ```

   This command will output the winner and the winning price according to the second-price auction rules.

## Running Tests

The application includes unit tests to ensure the correctness of the auction algorithm.

1. **Run All Tests**

   ```bash
   php artisan test
   ```

   or

   ```bash
   vendor/bin/phpunit
   ```

2. **Test Coverage (Optional)**

   If you wish to generate a test coverage report, you can use PHPUnit's coverage options. Ensure you have Xdebug or PCOV installed.

   ```bash
   vendor/bin/phpunit --coverage-html coverage/
   ```

   The coverage report will be generated in the `coverage/` directory.

## Code Structure and Explanation

The project follows the principles of Hexagonal Architecture (Ports and Adapters), separating the core business logic from the infrastructure and framework code.

### Domain Layer

- **Entities**: Located in `app/Domain/Entities`.

  - `Product`: Represents a product in the auction.
  - `Bid`: Represents a bid placed by a user.
  - `User`: Represents a user participating in the auction.

- **Repositories**: Interfaces defined in `app/Domain/Repositories`.

  - `ProductRepository`: Interface for product-related data operations.
  - `BidRepository`: Interface for bid-related data operations.

- **Services**: Located in `app/Domain/Services`.

  - `AuctionService`: Contains the core auction logic to determine the winner and the winning price.

### Application Layer

- **Use Cases**: Located in `app/Application/UseCases`.

  - `CreateProduct`: Handles the creation of new products.
  - `PlaceBid`: Handles the placement of bids on products.
  - `FinishBid`: Handles the finalization of auctions and determines winners.

### Infrastructure Layer

- **Repositories**: Implementations located in `app/Infrastructure/Repositories/Eloquent`.

  - `EloquentProductRepository`: Eloquent implementation of `ProductRepository`.
  - `EloquentBidRepository`: Eloquent implementation of `BidRepository`.

- **Models**: Eloquent models located in `app/Models`.

  - `Product`: Eloquent model for products.
  - `Bid`: Eloquent model for bids.
  - `User`: Eloquent model for users (using Laravel's default User model).

### Commands

Artisan commands are located in `app/Console/Commands`.

- `ListAvailableProducts`: Lists all products available for auction.
- `CreateProductCommand`: Creates a new product.
- `PlaceBid`: Places a bid on a product.
- `FinishAuction`: Finalizes the auction for a product.

### Testing

Tests are located in the `tests/` directory.

- `tests/Unit/Domain/Services/AuctionServiceTest.php`: Contains unit tests for the `AuctionService`, verifying the auction logic.

## Author

**Alejandro Ruesga Ortuño**

Feel free to reach out for any questions or further clarifications regarding the implementation.

---

## Problem Statement

Let's consider a second-price, sealed-bid auction:

An object is for sale with a reserve price.

We have several potential buyers, each one being able to place one or more bids.

The buyer winning the auction is the one with the highest bid above or equal to the reserve price.

The winning price is the highest bid price from a non-winning buyer above the reserve price (or the reserve price if none applies).

### Example

Consider 5 potential buyers (A, B, C, D, E) who compete to acquire an object with a reserve price set at 100 euros, bidding as follows:

- A: 2 bids of 110 and 130 euros
- B: 0 bid
- C: 1 bid of 125 euros
- D: 3 bids of 105, 115, and 90 euros
- E: 3 bids of 132, 135, and 140 euros

The buyer **E** wins the auction at the price of **130 euros**.

### Goal

The goal is to implement an algorithm for finding the winner and the winning price. Please implement the solution in PHP. Tests should be separated from your algorithm. We should be able to build and run your solution, tests, and code coverage locally without installing anything on our side.

### What do we expect?

When we evaluate the home task, we mainly focus on:

- **Algorithm**: Correctness and efficiency.
- **Complexity**: Time and space complexity considerations.
- **Test Coverage**: Comprehensive tests covering different scenarios.
- **Software Engineering Best Practices**:
  - Readability
  - Reusability
  - Idiomatic code style
  - Object-Oriented Programming (OOP)
  - SOLID principles

---

Thank you for reviewing this implementation. Your feedback is appreciated.