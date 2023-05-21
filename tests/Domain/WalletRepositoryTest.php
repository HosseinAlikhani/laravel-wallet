<?php
/**
 * test wallet repository
 * 1- test save event method success
 */
namespace D3CR33\Wallet\Test\Domain;

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
}