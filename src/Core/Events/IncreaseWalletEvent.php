<?php
namespace D3cr33\Wallet\Core\Events;

use D3cr33\Wallet\Core\Events\Contracts\WalletEventInterface;

final class IncreaseWalletEvent 
    extends WalletEvent
    implements WalletEventInterface
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
