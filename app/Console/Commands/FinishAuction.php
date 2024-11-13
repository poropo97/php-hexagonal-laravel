<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Application\UseCases\FinishBid;

class FinishAuction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auction:finish {productId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finalize the auction for a product and determine the winner';

    private FinishBid $finishBid;

    /**
     * Constructor to inject dependencies.
     */
    public function __construct(FinishBid $finishBid)
    {
        parent::__construct();
        $this->finishBid = $finishBid;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productId = (int)$this->argument('productId');

        try {
            // Ejecutar el caso de uso
            $result = $this->finishBid->execute($productId);
            $product = $result['product'];
            $winningBid = $result['winningBid'];
            $winningPrice = $result['winningPrice'];

            // Mostrar los resultados en la consola
            $this->info("La subasta para el producto '{$product->getName()}' ha finalizado.");
            $this->info("Puja ganadora: {$winningBid->getAmount()} por el usuario {$winningBid->getUser()->getId()}.");
            $this->info("Precio de venta: {$winningPrice} euros.");

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
