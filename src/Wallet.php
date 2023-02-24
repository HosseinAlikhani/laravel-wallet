<?php
namespace D3cr33\Wallet;

use D3cr33\Wallet\Events\Wallet\contracts\WalletEventInterface;
use D3cr33\Wallet\Events\Wallet\IncreaseWalletEvent;
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
     * @var string
     */
    public string $userId;

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

    private function __construct()
    {
        //
    }

    /**
     * initialize wallet aggregate
     * @return self
     */
    public static function initialize(): self
    {
        $instance = new self();
        $instance->setup();
        return $instance;
    }

    private function setup()
    {
        $this->uuid = Str::uuid();
        $this->createdAt = now();
    }

    /**
     * apply event on wallet
     * @param WalletEventInterface $walletEvent
     */
    public function apply(WalletEventInterface $walletEvent)
    {
        $class     = $walletEvent::class;
        $className = substr(strrchr($class, '\\'), 1);
        $method    = 'apply' . $className;
        if (method_exists($this, $method)) {
            $this->$method($walletEvent);

        }
    }

    /**
     * charge wallet
     * @param int $amount
     * @param $userId
     */
    public function increase(int $amount, $userId): self
    {
        $increaseEvent = new IncreaseWalletEvent(
            $this->uuid,
            $userId,
            $amount,
            $this->eventCount + 1
        );

        $this->apply($increaseEvent);
        return $this;
    }
}
