<?php
namespace D3cr33\Wallet\Ports;

use D3cr33\Wallet\Contracts\WalletResponseInterface;
use D3cr33\Wallet\Core\Repositories\WalletRepository;
use D3cr33\Wallet\Core\Wallet;
use D3cr33\Wallet\Resources\UserWalletResource;
use D3cr33\Wallet\Response\WalletResponse;
use Illuminate\Http\Response;

final class WalletPort
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

    /**
     * get user wallet
     * @param string $userId
     * @return WalletResponseInterface
     */
    public function userWallet(string $userId): WalletResponseInterface
    {
        $snapshot = $this->walletRepository->findSnapshotByUserId($userId);
        return new WalletResponse(
            Response::HTTP_OK,
            null,
            $snapshot ? (new UserWalletResource($snapshot))->response()->getData(true) : null
        );
    }

    /**
     * charge user wallet
     * @param string $userId
     * @param string $amount
     * @param string|null $description
     * @return WalletResponseInterface
     */
    public function walletCharge(string $userId, string $amount, string|null $description = null): WalletResponseInterface
    {
        $wallet = Wallet::initialize($userId)
            ->increase($amount,[
                'description'   =>  $description
            ]);
        return new WalletResponse(
            Response::HTTP_OK,
            trans('wallet::messages.wallet_charge_successfully'),
            (new UserWalletResource($wallet))->response()->getData(true)
        );
    }
}
