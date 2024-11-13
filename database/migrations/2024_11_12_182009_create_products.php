<?php

// database/migrations/xxxx_xx_xx_create_products_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domain\Entities\Product;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // name
            $table->string('name');
            // reserve price
            $table->decimal('reserve_price', 10, 2);
            // status
            $table->string('status')->default(Product::STATUS_AVAILABLE);
            // expires at
            $table->dateTime('expires_at');
            // winner user_id
            $table->integer('user_winner_id')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};

