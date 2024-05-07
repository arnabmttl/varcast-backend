<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\CoinPriceInterface;
use App\Repositories\CoinPriceRepository;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CoinPriceInterface::class, CoinPriceRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
