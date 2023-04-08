<?php
namespace D3CR33\Wallet\Test\Domain;

use D3cr33\Wallet\Core\Wallet;
use D3cr33\Wallet\Test\TestCase;

class WalletTest extends TestCase
{
    /**
     * test wallet initialize method without previous data
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

    /**
     * test wallet initialize method with previous data
     */
    public function test_wallet_initialize_with_previous_data()
    {
        $previousSnapshot = $this->faker->snapshot();

        $snapshot = Wallet::initialize($previousSnapshot->userId);

        $this->assertEquals( $previousSnapshot->toArray(), $snapshot->toArray() );
    }
}
