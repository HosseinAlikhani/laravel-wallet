<?php
namespace D3cr33\Wallet\Contracts;

interface WalletResponseInterface
{
    /**
     * get response data
     * @return array|string|null
     */
    public function getData(): array|string|null;

    /**
     * get response message
     * @return string|null
     */
    public function getMessage(): string|null;

    /**
     * is response successfull
     * @return bool
     */
    public function isSuccessfull(): bool;
}