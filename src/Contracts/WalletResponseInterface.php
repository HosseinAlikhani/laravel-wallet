<?php
namespace D3cr33\Wallet\Contracts;

interface WalletResponseInterface
{
    /**
     * get response data
     * @return array
     */
    public function getData(): array;
}