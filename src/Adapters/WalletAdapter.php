<?php
namespace D3cr33\Wallet\Adapters;

use D3cr33\Wallet\Contracts\WalletAdapterInterface;
use D3cr33\Wallet\Contracts\WalletResponseInterface;
use D3cr33\Wallet\Core\Repositories\WalletRepository;
use D3cr33\Wallet\Resources\UserWalletResource;
use D3cr33\Wallet\Response\WalletResponse;
use Illuminate\Http\Response;

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

    /**
     * get user wallet
     * @param string $userId
     * @return WalletResponseInterface
     */
    public function userWallet(string $userId): WalletResponseInterface
    {
        $snapshot = $this->walletRepository->findSnapshotByUserId($userId);
        if(! $snapshot){
            return (new WalletResponse(
                Response::HTTP_NOT_FOUND,
                trans(''),
                null
            ));
        }
        return new WalletResponse(
            Response::HTTP_OK,
            null,
            (new UserWalletResource($snapshot))->response()->getData(true)
        );
    }
}