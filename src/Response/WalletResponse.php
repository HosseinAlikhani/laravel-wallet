<?php
namespace D3cr33\Wallet\Response;

use D3cr33\Wallet\Contracts\WalletResponseInterface;

final class WalletResponse implements WalletResponseInterface
{
    /**
     * store status
     * @var int
     */
    private int $status;

    /**
     * store message
     * @var string
     */
    private string $message;

    /**
     * store data
     * @var array
     */
    private array $data;

    /**
     * constructor of wallet response
     * @param int $status
     * @param string $message
     * @param array $data
     * @param $args
     */
    private function __construct(
        int $status,
        string $message,
        array $data,
        ...$args
    )
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
}