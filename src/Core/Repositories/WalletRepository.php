<?php
namespace D3cr33\Wallet\Core\Repositories;

use D3cr33\Wallet\Core\Wallet;
use Illuminate\Support\Facades\DB;

final class WalletRepository
{
    /**
     * store table name
     */
    public const TABLE = 'wallet_snapshots';

    /**
     * find snapshot by user id
     * @param int $userId
     * @return Wallet|null
     */
    public function findSnapshotByUserId(int $userId): Wallet|null
    {
        $snapshot = DB::table(self::TABLE)
            ->where('user_id', $userId)->first();
        return $snapshot ? Wallet::toObject($snapshot) : null;
    }
}
