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
        $this->mergeConfigFrom(__DIR__.'/../config/wallet.php', 'wallet');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRequirements();
        $this->registerPublishing();
    }

    /**
     * load requirements section
     */
    private function loadRequirements()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'payment');
    }

    /**
     * register publishing file
     */
    private function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/../config/wallet.php' => config_path('wallet.php'),
        ], 'wallet-config');
    }
}
