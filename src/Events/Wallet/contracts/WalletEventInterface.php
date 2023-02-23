<?php
namespace D3cr33\Wallet\Events\Wallet\contracts;

interface WalletEventInterface
{
    /**
     * set uuid
     * @param string $uuid
     * @return WalletEventInterface
     */
    public function setUuid(string $uuid): WalletEventInterface;
}
