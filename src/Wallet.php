<?php
namespace D3cr33\Wallet;

use D3cr33\Wallet\Events\Wallet\contracts\WalletEventInterface;

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
    public int $eventCount;

    /**
     * dateTime when event raised
     * @var string
     */
    public string $createdAt;

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
}
