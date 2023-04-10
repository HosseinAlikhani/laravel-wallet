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
     * @var string|null
     */
    private string|null $message;

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
    public function __construct(
        int $status,
        string|null $message,
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
     * @param string|null $key
     * @return array|string|null
     */
    public function getData(string|null $key = null): array|string|null
    {
        if($key){
            return isset($this->data[$key]) ? $this->data[$key] : null;
        }
        
        return $this->data;
    }

    /**
     * get response message
     * @return string|null
     */
    public function getMessage(): string|null
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