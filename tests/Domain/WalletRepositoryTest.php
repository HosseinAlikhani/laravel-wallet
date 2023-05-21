<?php
/**
 * test wallet repository
 * 1- test save event method success
 * 2- test update or create method success
 * 3- test find event by uuid success
 * 4- test find snapshot by user id success
 * 5- test find last event by user id success
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

    /**
     * find event by uuid success
     */
    public function test_find_event_by_uuid_success()
    {
        $userId = $this->faker->userId();
        $wallet = Wallet::initialize($userId);

        $wallet->increase($this->faker->amount());

        $event = current( $wallet->recoredEvents );
        $findEvent = $this->faker->makeWalletRepository()->findEventByUuid($event->uuid);
        
        $this->assertInstanceOf( get_class($event), $findEvent );
        $this->assertEquals( $event->uuid, $findEvent->uuid );
    }

    /**
     * find snapshot by user id success
     */
    public function test_find_snapshot_by_user_id_success()
    {
        $userId = $this->faker->userId();
        $wallet = Wallet::initialize($userId);
        $wallet->increase($this->faker->amount());

        $snapshot = $this->faker->makeWalletRepository()->findSnapshotByUserId($userId);
        $wallet->recoredEvents = [];
        $this->assertEquals($wallet, $snapshot);
    }

    /**
     * find last event by user id success
     */
    public function test_find_last_event_by_user_id_success()
    {
        $userId = $this->faker->userId();
        $wallet = Wallet::initialize($userId);
        $wallet->increase($this->faker->amount());

        $event = current( $wallet->recoredEvents );
        $lastEvent = $this->faker->makeWalletRepository()->findLastEventByUserId($userId);

        $this->assertInstanceOf( get_class($event), $lastEvent );
        $this->assertEquals( $event->uuid, $lastEvent->uuid );
    }

    /**
     * find user events by user id success
     */
    public function test_find_user_events_by_user_id_success()
    {
        $userId = $this->faker->userId();
        $wallet = Wallet::initialize($userId);
        $wallet->increase($this->faker->amount());
        $wallet->increase($this->faker->amount());
        $wallet->decrease($this->faker->amount());
        $wallet->increase($this->faker->amount());
        $wallet->decrease($this->faker->amount());

        $events = $this->faker->makeWalletRepository()->findUserEventsByUserId($userId,[
            'order_by'  =>  'ASC',
            'order_by_type' =>  'event_count'
        ])->toArray();

        $recordedEvents = [];
        foreach($wallet->recoredEvents as $event){
            $recordedEvents[] = $event->toArray();
        }

        foreach( $events as $key => $event ){
            $events[$key] = (array) $event;
        }
        $this->assertCount(count($recordedEvents), $events);
        $this->assertEquals( $recordedEvents, $events );
    }
}