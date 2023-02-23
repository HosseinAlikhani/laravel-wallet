<?php
namespace D3cr33\Wallet\Events\Wallet;

use D3cr33\Wallet\Events\Wallet\contracts\WalletEventInterface;

class WalletEvent implements WalletEventInterface
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

    /**
     * set uuid
     * @param string $uuid
     * @return WalletEventInterface
     */
    public function setUuid(string $uuid): WalletEventInterface
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * get uuid
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
