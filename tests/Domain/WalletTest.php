<?php
/**
 * test wallet aggregate
 * 1- test wallet initialize method without previous data ( not found user id in snapshot table )
 * 2- test wallet initialize method with previous data ( find snapshot from user id )
 * 3- test wallet findSnapshot without find anything ( without find snapshot from user id)
 * 4- test wallet findSnapshot with find snapshot ( find snapshot from user id)
 * 5- test wallet apply method when increaseWalletEvent exist and check properties after updated aggregate
 * 6- test wallet apply method when decreaseWalletEvent exist and check proprties after update aggregate
 * 7- test wallet recordEvents method with single event
 */
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

    /**
     * test increase wallet event with apply method
     */
    public function test_apply_method_with_increase_wallet_event()
    {
        $wallet = Wallet::initialize($this->faker->userId());
        $increaseWallet = $this->faker->increaseWalletEvent();
        $result = $this->faker->invokeProtectMethod($wallet, 'apply', [$increaseWallet]);

        $this->assertTrue($result);
        $this->assertEquals( $increaseWallet->uuid, $wallet->uuid );
        $this->assertEquals( $increaseWallet->amount, $wallet->amount );
        $this->assertEquals( $increaseWallet->eventCount, $wallet->eventCount );
        $this->assertEquals( $increaseWallet->getEventType(), $wallet->eventType );
        $this->assertEquals( $increaseWallet->createdAt, $wallet->createdAt );
    }

    /**
     * test decrease wallet event with apply method
     */
    public function test_apply_method_with_decrease_wallet_event()
    {
        $wallet = Wallet::initialize($this->faker->userId());
        $decreaseWallet = $this->faker->decreaseWalletEvent();
        $result = $this->faker->invokeProtectMethod($wallet, 'apply', [$decreaseWallet]);

        $this->assertTrue($result);
        $this->assertEquals( $decreaseWallet->uuid, $wallet->uuid );
        $this->assertEquals( $decreaseWallet->amount, $wallet->amount );
        $this->assertEquals( $decreaseWallet->eventCount, $wallet->eventCount );
        $this->assertEquals( $decreaseWallet->getEventType(), $wallet->eventType );
        $this->assertEquals( $decreaseWallet->createdAt, $wallet->createdAt );
    }

    /**
     * test record events method with single event
     */
    public function test_record_events_with_single_event()
    {
        $wallet = Wallet::initialize($this->faker->userId());
        $decreaseWallet = $this->faker->decreaseWalletEvent();
        $this->faker->invokeProtectMethod($wallet, 'recordEvent', [$decreaseWallet]);

        $this->assertEquals( $wallet->recoredEvents, [$decreaseWallet]);
    }
}
