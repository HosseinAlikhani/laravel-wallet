<?php
namespace D3cr33\Wallet\Core\Repositories;

use D3cr33\Wallet\Core\Events\Contracts\WalletEventInterface;
use D3cr33\Wallet\Core\Events\WalletEvent;
use D3cr33\Wallet\Core\Wallet;
use Illuminate\Support\Facades\DB;

final class WalletRepository
{
    /**
     * store snapshot table name
     */
    public const TABLE_SNAPSHOT = 'wallet_snapshots';

    /**
     * store event table name
     */
    public const TABLE_EVENT = 'wallet_events';

    /**
     * find last event by user id
     * @param int $userId
     * @return WalletEvent|null
     */
    public function findLastEventByUserId(int $userId): WalletEvent|null
    {
        $result = DB::table(self::TABLE_EVENT)
            ->where('user_id', $userId)
            ->orderByDesc('event_count')
            ->first();
        if(! $result){
            return null;
        }

        $result->detail = json_decode($result->detail);
        return WalletEvent::toObject($result);
    }

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

    /**
     * create wallet event
     * @param WalletEventInterface $walletEvent
     * @return bool
     */
    public function createEvent(WalletEventInterface $walletEvent)
    {
        $data = $walletEvent->toArray();
        $data['detail'] = json_encode($data['detail']);
        return DB::table(self::TABLE_EVENT)->insert($data);
    }
}
