<?php
namespace D3CR33\Wallet\Test\Domain;

use D3cr33\Wallet\Core\Wallet;
use D3cr33\Wallet\Test\TestCase;

class WalletTest extends TestCase
{
    /**
     * update test wallet initialize method
     */
    public function test_wallet_initialize_without_previous_data()
    {
        $userId = fake()->numberBetween(1000, 9999);
        $wallet = Wallet::initialize($userId);

        $this->assertNotNull($wallet->uuid);
        $this->assertEquals($userId, $wallet->userId);
        $this->assertEquals(0, $wallet->amount);
        $this->assertEquals(0, $wallet->balance);
        $this->assertNull($wallet->eventType);
        $this->assertEquals(0, $wallet->eventCount);
    }
}
