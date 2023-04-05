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
     * @var array|null
     */
    private array|null $data;

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
        array|null $data,
        ...$args
    )
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * get response data
     * @return array|null
     */
    public function getData(): array|null
    {
        return $this->data;
    }

    /**
     * get response message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * is response successfull
     * @return bool
     */
    public function isSuccessfull(): bool
    {
        return $this->status == 200 || $this->status || 201 ? true : false;
    }
}