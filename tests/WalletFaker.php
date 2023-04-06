<?php
namespace D3cr33\Wallet\Test;

use D3cr33\Wallet\Core\Wallet;

final class WalletFaker
{
    /**
     * generate fake snapshot
     * @param array $state
     * @return Wallet
     */
    public function snapshot(array $state = []): Wallet
    {
        return (Wallet::initialize($this->userId()))->increase($this->amount());
    }

    /**
     * generate fake user id
     * @return string
     */
    public function userId(): string
    {
        return fake()->uuid();
    }

    /**
     * generate amount
     * @return int
     */
    public function amount(): int
    {
        return fake()->numerify("###000");
    }
}
