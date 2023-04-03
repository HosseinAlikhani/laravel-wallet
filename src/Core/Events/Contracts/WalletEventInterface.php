<?php
namespace D3cr33\Wallet\Core\Events\Contracts;

interface WalletEventInterface
{
    /**
     * to array wallet event
     * @return array
     */
    public function toArray(): array;

    /**
     * get event type
     * @return string
     */
    public function getEventType(): string;
}
