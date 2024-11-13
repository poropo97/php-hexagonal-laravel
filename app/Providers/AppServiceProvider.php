<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\ProductRepository;
use App\Domain\Repositories\BidRepository;
use App\Infrastructure\Repositories\Eloquent\EloquentProductRepository;
use App\Infrastructure\Repositories\Eloquent\EloquentBidRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // bind the interfaces to the implementations
        $this->app->bind(ProductRepository::class,  EloquentProductRepository::class);
        $this->app->bind(BidRepository::class,      EloquentBidRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
