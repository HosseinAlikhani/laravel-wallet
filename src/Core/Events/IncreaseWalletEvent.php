<?php
namespace D3cr33\Wallet\Core\Events;

final class IncreaseWalletEvent extends WalletEvent
{
    public const EVENT_TYPE = 'IncreaseWallet';

    /**
     * get event type
     * @return string
     */
    public function getEventType(): string
    {
        return self::EVENT_TYPE;
    }
}
