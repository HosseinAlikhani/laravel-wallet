<?php
namespace D3cr33\Wallet\Test;

use D3cr33\Wallet\WalletServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * store wallet faker
     */
    protected WalletFaker $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = app(WalletFaker::class);
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'mysql',
            'host' =>   '127.0.0.1',
            'port' => '3306',
            'database' => 'laravel-wallet',
            'username' => 'root',
            'password' => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [WalletServiceProvider::class];
    }
}
