<?php

// database/migrations/xxxx_xx_xx_create_bids_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            // user id who made the bid
            $table->integer('user_id');
            // product id
            $table->integer('product_id');
            // amount of the bid
            $table->decimal('amount', 10, 2); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bids');
    }
};
