<?php
namespace D3cr33\Wallet\Core\Repositories;

use D3cr33\Wallet\Core\Wallet;
use Illuminate\Support\Facades\DB;

final class WalletRepository
{
    /**
     * store table name
     */
    public const TABLE_SNAPSHOT = 'wallet_snapshots';

    /**
     * find snapshot by user id
     * @param int $userId
     * @return Wallet|null
     */
    public function findSnapshotByUserId(int $userId): Wallet|null
    {
        $snapshot = DB::table(self::TABLE_SNAPSHOT)
            ->where('user_id', $userId)->first();
        return $snapshot ? Wallet::toObject($snapshot) : null;
    }

    /**
     * update or create snapshot
     * @param Wallet $wallet
     * @return bool
     */
    public function updateOrCreateSnapshot(Wallet $wallet): bool
    {
        return DB::table(self::TABLE_SNAPSHOT)->updateOrInsert(
            ['user_id'   =>  $wallet->userId],
            array_merge($wallet->toArray(), ['updated_at' => now()])
        );
    }
}
