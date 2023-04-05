<?php
namespace D3cr33\Wallet\Contracts;

interface WalletAdapterInterface
{
    /**
     * get user wallet
     * @param int $userId
     * @return WalletResponseInterface
     */
    public function userWallet(int $userId): WalletResponseInterface;
}