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
 * 8- test wallet recordEvents method with multi events
 * 9- test wallet increase/decrease method with amount
 * 10- test wallet increase/decrease methods with amount + detail
 */
namespace D3CR33\Wallet\Test\Domain;

use D3cr33\Wallet\Core\Events\IncreaseWalletEvent;
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

    /**
     * test recordEvents method with multi events
     */
    public function test_record_events_with_multi_events()
    {
        $wallet = Wallet::initialize($this->faker->userId());
        $decreaseWallet1 = $this->faker->decreaseWalletEvent();
        $this->faker->invokeProtectMethod($wallet, 'recordEvent', [$decreaseWallet1]);

        $decreaseWallet2 = $this->faker->decreaseWalletEvent();
        $this->faker->invokeProtectMethod($wallet, 'recordEvent', [$decreaseWallet2]);

        $increaseWallet3 = $this->faker->increaseWalletEvent();
        $this->faker->invokeProtectMethod($wallet, 'recordEvent', [$increaseWallet3]);

        $increaseWallet4 = $this->faker->increaseWalletEvent();
        $this->faker->invokeProtectMethod($wallet, 'recordEvent', [$increaseWallet4]);

        $increaseWallet5 = $this->faker->increaseWalletEvent();
        $this->faker->invokeProtectMethod($wallet, 'recordEvent', [$increaseWallet5]);

        $decreaseWallet6 = $this->faker->decreaseWalletEvent();
        $this->faker->invokeProtectMethod($wallet, 'recordEvent', [$decreaseWallet6]);

        $this->assertEquals($decreaseWallet1, $wallet->recoredEvents[0]);
        $this->assertEquals($decreaseWallet2, $wallet->recoredEvents[1]);
        $this->assertEquals($increaseWallet3, $wallet->recoredEvents[2]);
        $this->assertEquals($increaseWallet4, $wallet->recoredEvents[3]);
        $this->assertEquals($increaseWallet5, $wallet->recoredEvents[4]);
        $this->assertEquals($decreaseWallet6, $wallet->recoredEvents[5]);
    }

    /*
     * - test wallet increase method one time
     * - check wallet instance after increase amount
     * - initialize again wallet with user id
     * - check wallet instance after decrease with amount
     */
    public function test_increase_and_decrease_methods_with_amount()
    {
        $userId = $this->faker->userId();
        $wallet = Wallet::initialize($userId);
        $walletUuidBeforeIncrease = $wallet->uuid;

        $stepOneAmount = $this->faker->amount();
        $createAt = now();
        $wallet->increase($stepOneAmount);

        $this->assertNotEquals($walletUuidBeforeIncrease, $wallet->uuid);
        $this->assertEquals($stepOneAmount, $wallet->amount);
        $this->assertEquals($stepOneAmount, $wallet->balance);
        $this->assertEquals(1, $wallet->eventCount);
        $this->assertEquals($createAt, $wallet->createdAt);
        $this->assertCount(1, $wallet->recoredEvents);

        $recordEvent = current($wallet->recoredEvents);
        $this->assertInstanceOf(IncreaseWalletEvent::class, $recordEvent);

        $snapshotWallet = Wallet::initialize($userId);
        $this->assertEquals($stepOneAmount, $snapshotWallet->amount);
        $this->assertEquals($stepOneAmount, $snapshotWallet->balance);
        $this->assertEquals(IncreaseWalletEvent::EVENT_TYPE, $snapshotWallet->eventType);
        $this->assertEquals(1, $snapshotWallet->eventCount);

        $stepTwoAmount = $this->faker->amount();
        $createAt = now();
        $walletUuidBeforeDecrease = $wallet->uuid;
        $wallet->decrease($stepTwoAmount);

        $this->assertNotEquals($walletUuidBeforeDecrease, $wallet->uuid);
        $this->assertEquals($stepTwoAmount, $wallet->amount);
        $this->assertEquals($stepOneAmount - $stepTwoAmount, $wallet->balance);
        $this->assertEquals(2, $wallet->eventCount);
        $this->assertEquals($createAt, $wallet->createdAt);
        $this->assertCount(2, $wallet->recoredEvents);
    }

    /**
     * test increase & decrease methods with amount and details params and check details
     */
    public function test_increase_and_decrease_methods_with_amount_details()
    {
        $userId = $this->faker->userId();
        $wallet = Wallet::initialize($userId);

        // check detail in increase method
        $amount = $this->faker->amount();
        $detail = $this->faker->eventDetail();
        $wallet->increase($amount, $detail);

        $recordedEvent = current($wallet->recoredEvents);
        $this->assertEquals($detail, $recordedEvent->detail);

        // check detail in decrease method
        $detail = $this->faker->eventDetail();
        $wallet->increase($amount, $detail);

        $recordedEvent = end($wallet->recoredEvents);
        $this->assertEquals($detail, $recordedEvent->detail);
    }
}
