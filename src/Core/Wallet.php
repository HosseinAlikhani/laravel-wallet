<?php
namespace D3cr33\Wallet\Core;

use D3cr33\Wallet\Core\Repositories\WalletRepository;
use D3cr33\Wallet\Core\Events\Contracts\WalletEventInterface;
use D3cr33\Wallet\Core\Events\DecreaseWalletEvent;
use D3cr33\Wallet\Core\Events\IncreaseWalletEvent;
use Exception;
use Illuminate\Support\Str;

final class Wallet
{
    /**
     * event uuid - unique id
     * @var string
     */
    public string $uuid;

    /**
     * event raise on which user authenticate id
     * @var string|null
     */
    public string|null $userId;

    /**
     * amount of event
     * @var int
     */
    public int $amount;

    /**
     * balance of event
     * @var int
     */
    public int $balance;

    /**
     * store event type
     * @var string
     */
    public string|null $eventType;

    /**
     * count of event that applied to aggregate
     * @var int
     */
    public int $eventCount = 0;

    /**
     * dateTime when event raised
     * @var string
     */
    public string $createdAt;

    /**
     * recorded events
     * @var array
     */
    public array $recoredEvents = [];

    /**
     * store wallet repository
     * @var WalletRepository
     */
    private WalletRepository $walletRepository;

    /**
     * wallet constructor
     * @param string $uuid
     * @param string|null $userId
     * @param int $amount
     * @param int $balance
     * @param int $eventCount
     * @param string $createdAt
     */
    private function __construct(
        string $uuid,
        string|null $userId,
        int $amount,
        int $balance,
        string|null $eventType,
        int $eventCount,
        string $createdAt
    )
    {
        $this->uuid = $uuid;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->balance = $balance;
        $this->eventType = $eventType;
        $this->eventCount = $eventCount;
        $this->createdAt = $createdAt;

        $this->walletRepository = new WalletRepository();
    }

    /**
     * initialize wallet aggregate
     * @param string $userId
     * @return Wallet
     */
    public static function initialize(string $userId): Wallet
    {
        $instance = new self(Str::uuid(), $userId, 0, 0, null, 0, now() );
        $snapshot = $instance->findSnapshot($userId);
        return $snapshot ?? $instance;
    }

    /**
     * restore wallet from snapshot records
     * @param string $user
     * @return Wallet|null
     */
    private function findSnapshot(string $userId): Wallet|null
    {
        return $this->walletRepository->findSnapshotByUserId($userId);
    }

    /**
     * apply event on wallet
     * @param WalletEventInterface $walletEvent
     * @return bool
     */
    private function apply(WalletEventInterface $walletEvent): bool
    {
        $class     = $walletEvent::class;
        $className = substr(strrchr($class, '\\'), 1);
        $method    = 'apply' . $className;
        if(! method_exists($this, $method)){
            return false;
        }

        // check if event count is not valid, do not apply event
        if(! $this->updateEventCount($walletEvent->eventCount) ){
            return false;
        }

        $this->$method($walletEvent);
        $this->uuid = $walletEvent->uuid;
        $this->eventType = $walletEvent->getEventType();
        $this->createdAt = $walletEvent->createdAt;
        $this->recordEvent($walletEvent);
        return true;
    }

    /**
     * update wallet event count
     * @param int $eventCount
     * @return bool
     */
    private function updateEventCount(int $eventCount)
    {
        if(! $this->eventCount){
            $this->eventCount = $eventCount;
            return true;
        }

        if($this->eventCount + 1 != $eventCount){
            //TODO need log message
            return false;
        }

        $this->eventCount = $eventCount;
        return true;
    }

    /**
     * increase wallet event
     * @param IncreaseWalletEvent $event
     * @return void
     */
    private function applyIncreaseWalletEvent(IncreaseWalletEvent $event): void
    {
        $this->amount = $event->amount;
        $this->balance += $event->amount;
    }

    /**
     * decrease wallet event
     * @param DecreaseWalletEvent $event
     * @return void
     */
    private function applyDecreaseWalletEvent(DecreaseWalletEvent $event): void
    {
        $this->amount = $event->amount;
        $this->balance -= $event->amount;
    }

    /**
     * recored apply events
     * @param WalletEventInterface $walletEvent
     * @return void
     */
    private function recordEvent(WalletEventInterface $walletEvent): void
    {
        $this->recoredEvents = array_merge($this->recoredEvents, [$walletEvent]);
    }

    /**
     * charge wallet
     * @param int $amount
     * @param array $detail
     * @return Wallet
     */
    public function increase(int $amount, array $detail = []): Wallet
    {
        $increaseEvent = IncreaseWalletEvent::initialize(
            $this->userId,
            $amount,
            $this->eventCount + 1,
            now(),
            $detail
        );

        $this->apply($increaseEvent);
        $this->saveEvent($increaseEvent);
        $this->saveSnapshot();
        return $this;
    }

    /**
     * decrease wallet
     * @param int $amount
     * @param array $detail
     * @return Wallet
     */
    public function decrease(int $amount, array $detail = []): Wallet
    {
        $decreaseEvent = DecreaseWalletEvent::initialize(
            $this->userId,
            $amount,
            $this->eventCount + 1,
            now(),
            $detail
        );
        $this->apply($decreaseEvent);
        $this->saveEvent($decreaseEvent);
        $this->saveSnapshot();
        return $this;
    }

    /**
     * save wallet snapshot
     * @return bool
     */
    private function saveSnapshot(): bool
    {
        return $this->walletRepository->updateOrCreateSnapshot($this);
    }

    /**
     * save event
     * @param WalletEventInterface $walletEvent
     * @return bool
     */
    private function saveEvent(WalletEventInterface $walletEvent): bool
    {
        $this->checkEventCount($walletEvent);

        return $this->walletRepository->createEvent($walletEvent);
    }

    /**
     * check wallet event count
     * @param WalletEventInterface $walletEvent
     * @return bool
     */
    private function checkEventCount(WalletEventInterface $walletEvent): bool
    {
        $lastEvent = $this->walletRepository->findLastEventByUserId($walletEvent->userId);
        if( $lastEvent && $lastEvent->eventCount != $walletEvent->eventCount - 1 ){
            throw new Exception(trans('wallet::messages.event_count_not_valid'));
        }
        return true;
    }

    /**
     * convert std class to wallet object
     * @param object $wallet
     * @return Wallet
     */
    public static function toObject(object $wallet): Wallet
    {
        return new self(
            $wallet->uuid,
            $wallet->user_id,
            $wallet->amount,
            $wallet->balance,
            $wallet->event_type,
            $wallet->event_count,
            $wallet->created_at
        );
    }

    /**
     * to array wallet object
     * @return array
     */
    public function toArray(): array
    {
        return [
            'uuid'  =>  $this->uuid,
            'user_id'   =>  $this->userId,
            'amount'    =>  $this->amount,
            'balance'   =>  $this->balance,
            'event_type'    =>  $this->eventType,
            'event_count'   =>  $this->eventCount,
            'created_at'    =>  $this->createdAt
        ];
    }
}
