<?php
namespace D3cr33\Wallet\Events;

use D3cr33\Wallet\Events\Wallet\contracts\WalletEventInterface;

class WalletEvent implements WalletEventInterface
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
     * create new instance of wallet event
     * @param string $uuid
     * @param string $userId
     * @param int $amount
     * @param int $eventCount
     */
    public function __construct(
        string $uuid,
        string $userId,
        int $amount,
        int $eventCount
    )
    {
        $this->uuid = $uuid;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->eventCount = $eventCount;
    }
}
