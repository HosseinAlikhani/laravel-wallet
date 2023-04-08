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

    /**
     * invoke class protected method
     * @param object $object
     * @param string $methodName
     * @param array $params
     */
    public function invokeProtectMethod(object $object,string $methodName,array $params)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $params);
    }
}
