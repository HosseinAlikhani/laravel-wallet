<?php
namespace D3cr33\Wallet\Core\Events;

final class DecreaseWalletEvent extends WalletEvent
{
    public const EVENT_TYPE = 'DecreaseWallet';

    /**
     * get event type
     * @return string
     */
    public function getEventType(): string
    {
        return self::EVENT_TYPE;
    }
}
