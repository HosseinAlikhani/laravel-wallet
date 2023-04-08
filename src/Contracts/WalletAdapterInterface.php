<?php
namespace D3cr33\Wallet\Contracts;

interface WalletAdapterInterface
{
    /**
     * get user wallet
     * @param string $userId
     * @return WalletResponseInterface
     */
    public function userWallet(string $userId): WalletResponseInterface;
}
