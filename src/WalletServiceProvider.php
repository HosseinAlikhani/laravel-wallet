<?php
namespace D3cr33\Wallet;

use Illuminate\Support\ServiceProvider;

class WalletServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrtions();
    }

    /**
     * load wallet migrations
     */
    private function loadMigrtions()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    }
}