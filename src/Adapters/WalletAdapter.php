<?php
namespace D3cr33\Wallet\Adapters;

use D3cr33\Wallet\Core\Repositories\WalletRepository;

final class WalletAdapter implements WalletAdapterInterface
{
    /**
     * store wallet repository
     * @var WalletRepository
     */
    private WalletRepository $walletRepository;

    public function __construct(WalletRepository $walletRepository)
    {
        $this->walletRepository = $walletRepository;    
    }
}