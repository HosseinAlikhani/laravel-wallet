<?php
namespace D3cr33\Wallet\Test;

use D3cr33\Wallet\Core\Events\Contracts\WalletEventInterface;
use D3cr33\Wallet\Core\Events\DecreaseWalletEvent;
use D3cr33\Wallet\Core\Events\IncreaseWalletEvent;
use D3cr33\Wallet\Core\Repositories\WalletRepository;
use D3cr33\Wallet\Core\Wallet;

final class WalletFaker
{
    /**
     * store wallet repository
     * @var WalletRepository
     */
    private ?WalletRepository $walletRepository = null;

    /**
     * make wallet repository
     */
    public function makeWalletRepository(): WalletRepository
    {
        return $this->walletRepository ?? app(WalletRepository::class);
    }

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
     * generate increase wallet event
     * @return WalletEventInterface
     */
    public function increaseWalletEvent(): WalletEventInterface
    {
        return IncreaseWalletEvent::initialize(
            $this->userId(),
            $this->amount(),
            fake()->numberBetween(100, 9999),
            fake()->dateTime()->format('Y-m-d H:i:s'),
            []
        );
    }

    /**
     * generate decrease wallet event
     * @return WalletEventInterface
     */
    public function decreaseWalletEvent(): WalletEventInterface
    {
        return DecreaseWalletEvent::initialize(
            $this->userId(),
            $this->amount(),
            fake()->numberBetween(100, 9999),
            fake()->dateTime()->format('Y-m-d H:i:s'),
            []
        );
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
     * generate fake event detail
     */
    public function eventDetail(): array
    {
        return [
            'requester_id'  =>  $this->userId(),
            'requester_first_name'  =>  fake()->firstName(),
            'requester_last_name'   =>  fake()->lastName(),
            'trigger_from'  =>  fake()->name()
        ];
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
