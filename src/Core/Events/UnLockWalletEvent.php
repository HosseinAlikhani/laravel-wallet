<?php
namespace D3cr33\Wallet\Core\Events;

use D3cr33\Wallet\Core\Events\Contracts\WalletEventInterface;

final class UnLockWalletEvent
    extends WalletEvent
    implements WalletEventInterface
{
    public const EVENT_TYPE = 'UnLockWallet';

    /**
     * get event type
     * @return string
     */
    public function getEventType(): string
    {
        return self::EVENT_TYPE;
    }
}
