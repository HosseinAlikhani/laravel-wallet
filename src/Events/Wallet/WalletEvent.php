<?php
namespace D3cr33\Wallet\Events\Wallet;

class WalletEvent
{
    /**
     * event uuid - unique id
     * @var string
     */
    private string $uuid;

    /**
     * event raise on which user authenticate id
     * @var string
     */
    private string $userId;

    /**
     * amount of event
     * @var int
     */
    private int $amount;

    /**
     * balance of event
     * @var int
     */
    private int $balance;

    /**
     * count of event that applied to aggregate
     * @var int
     */
    private int $eventCount;

    /**
     * dateTime when event raised
     * @var string
     */
    private string $createdAt;
}
