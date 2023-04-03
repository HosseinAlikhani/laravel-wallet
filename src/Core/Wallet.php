<?php
namespace D3cr33\Wallet\Core;

use D3cr33\Wallet\Core\Repositories\WalletRepository;
use D3cr33\Wallet\Core\Events\Contracts\WalletEventInterface;
use D3cr33\Wallet\Core\Events\DecreaseWalletEvent;
use D3cr33\Wallet\Core\Events\IncreaseWalletEvent;
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
        int $eventCount,
        string $createdAt
    )
    {
        $this->uuid = $uuid;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->balance = $balance;
        $this->eventCount = $eventCount;
        $this->createdAt = $createdAt;

        $this->walletRepository = new WalletRepository();
    }

    /**
     * initialize wallet aggregate
     * @return self
     */
    public static function initialize(): self
    {
        $instance = new self(
            Str::uuid(),
            null,
            0,
            0,
            0,
            now(),
        );
        return $instance;
    }

    /**
     * restore wallet from snapshot records
     * @param int $user
     * @return Wallet|null
     */
    private function findSnapshot(int $userId): Wallet|null
    {
        return $this->walletRepository->findSnapshotByUserId($userId);
    }

    /**
     * apply event on wallet
     * @param WalletEventInterface $walletEvent
     */
    private function apply(WalletEventInterface $walletEvent)
    {
        $class     = $walletEvent::class;
        $className = substr(strrchr($class, '\\'), 1);
        $method    = 'apply' . $className;
        if (method_exists($this, $method)) {
            $this->$method($walletEvent);
            $this->balance = $this->balance + $this->amount;
            $this->userId = $walletEvent->userId;
            $this->eventCount = $walletEvent->eventCount;
            $this->createdAt = $walletEvent->createdAt;
            $this->recordEvent($walletEvent);
        }
    }

    /**
     * increase wallet event
     * @param IncreaseWalletEvent $event
     * @return void
     */
    private function applyIncreaseWalletEvent(IncreaseWalletEvent $event): void
    {
        $this->amount += $event->amount;
    }

    /**
     * decrease wallet event
     * @param DecreaseWalletEvent $event
     * @return void
     */
    private function applyDecreaseWalletEvent(DecreaseWalletEvent $event): void
    {
        $this->amount -= $event->amount;
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
     * @param int $userId
     * @return Wallet
     */
    public function increase(int $amount, int $userId): Wallet
    {
        $increaseEvent = new IncreaseWalletEvent(
            $userId,
            $amount,
            $this->eventCount + 1,
            now()
        );

        $this->apply($increaseEvent);
        $this->saveSnapshot();
        return $this;
    }

    /**
     * decrease wallet
     * @param int $amount
     * @param int $userId
     * @return Wallet
     */
    public function decrease(int $amount, int $userId): Wallet
    {
        $decreaseEvent = new DecreaseWalletEvent(
            $userId,
            $amount,
            $this->eventCount + 1,
            now(),
        );
        $this->apply($decreaseEvent);
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
            'event_count'   =>  $this->eventCount,
            'created_at'    =>  $this->createdAt
        ];
    }
}
