<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\PixKey\Repositories\PixKeyRepositoryInterface;
use App\Domain\PixTransaction\Repositories\PixTransactionRepositoryInterface;
use Infrastructure\Persistence\Mock\Repositories\PixKeyMockRepository;
use Infrastructure\Persistence\Mock\Repositories\PixTransactionMockRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // app/Providers/AppServiceProvider.php
    public function register()
    {
        $this->app->bind(
            \Domain\Auth\Repositories\UserRepositoryInterface::class,
            \Infrastructure\Persistence\Mock\Repositories\UserMockRepository::class
        );
        
        // Registrar os repositórios mockados para PIX
        $this->app->bind(
            PixKeyRepositoryInterface::class,
            PixKeyMockRepository::class
        );
        
        $this->app->bind(
            PixTransactionRepositoryInterface::class,
            PixTransactionMockRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
