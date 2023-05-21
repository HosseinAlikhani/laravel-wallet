<?php
/**
 * test wallet repository
 * 1- test save event method success
 * 2- test update or create method success
 */
namespace D3CR33\Wallet\Test\Domain;

use D3cr33\Wallet\Core\Events\IncreaseWalletEvent;
use D3cr33\Wallet\Core\Wallet;
use D3cr33\Wallet\Test\TestCase;

class WalletRepositoryTest extends TestCase
{
    /**
     * test save event method success
     */
    public function test_save_event_success()
    {
        $eventInstance = $this->faker->increaseWalletEvent();
        $result = $this->faker->makeWalletRepository()->createEvent($eventInstance);
        $this->assertTrue($result);

        $event = $this->faker->makeWalletRepository()->findEventByUuid($eventInstance->uuid);
        $this->assertEquals( $eventInstance, $event );
    }

    /**
     * test update or create method success
     */
    public function test_update_or_create_method_success()
    {
        $userId = $this->faker->userId();
        $wallet = Wallet::initialize($userId);
        $wallet->eventType = IncreaseWalletEvent::EVENT_TYPE;
        $this->assertTrue(
            $this->faker->makeWalletRepository()->updateOrCreateSnapshot($wallet)
        );

        $snapshot = $this->faker->makeWalletRepository()->findSnapshotByUserId( $userId );

        $this->assertInstanceOf(Wallet::class, $snapshot);
        $this->assertEquals( $wallet, $snapshot );
    }
}