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
        $this->loadMigrtions();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * load wallet migrations
     */
    private function loadMigrtions()
    {
        $this->loadMigrationsFrom('/../database/migrations');

    }
}