<?php
namespace D3cr33\Wallet;

use D3cr33\Wallet\Events\Wallet\contracts\WalletEventInterface;

final class Wallet
{
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
