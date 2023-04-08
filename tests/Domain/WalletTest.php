<?php
/**
 * test wallet aggregate
 * 1- test wallet initialize method without previous data ( not found user id in snapshot table )
 * 2- test wallet initialize method with previous data ( find snapshot from user id )
 * 3- test wallet findSnapshot without find anything ( without find snapshot from user id)
 * 4- test wallet findSnapshot with find snapshot ( find snapshot from user id)
 */
namespace D3CR33\Wallet\Test\Domain;

use D3cr33\Wallet\Core\Wallet;
use D3cr33\Wallet\Test\TestCase;
use ReflectionClass;

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

    /**
     * test wallet findSnapshot without find anything
     */
    public function test_wallet_find_snapshot_return_null()
    {
        $walletObject = Wallet::initialize($this->faker->userId());
        $result = $this->faker->invokeProtectMethod($walletObject, 'findSnapshot', [$walletObject->userId]);
        $this->assertNull($result);
    }

    /**
     * test wallet findSnapshot with find object
     */
    public function test_wallet_find_snapshot_return_snapshot()
    {
        $previousSnapshot = $this->faker->snapshot();

        $walletObject = Wallet::initialize($this->faker->userId());
        $result = $this->faker->invokeProtectMethod($walletObject, 'findSnapshot', [$previousSnapshot->userId]);

        $this->assertEquals( $previousSnapshot->toArray(), $result->toArray() );
    }
}
