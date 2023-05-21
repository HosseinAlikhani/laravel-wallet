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
     * find event by uuid
     * @param string $uuid
     * @return ?WalletEvent
     */
    public function findEventByUuid(string $uuid): ?WalletEvent
    {
        $event = DB::table(self::TABLE_EVENT)
            ->where('uuid', $uuid)
            ->first();        
        $event->detail = (array) json_decode($event->detail);
        return $event ? WalletEvent::toObject((array) $event) : null;
    }

    /**
     * find user events by user id
     * @param string $userId
     * @param array $filters
     * @param string $filters[type]
     * @param string $filters[order_by]
     * @param string $filters[order_by_type]
     * @param int|null $paginate
     */
    public function findUserEventsByUserId(string $userId, array $filters = [], int|null $paginate = null)
    {
        $result = DB::table(self::TABLE_EVENT)
            ->where('user_id', '=', $userId);
        
        if( isset($filters['type']) ){
            $result = $result->where('event_type', 'like', '%'.$filters['type'].'%');
        }

        if( isset($filters['order_by']) && isset($filters['order_by_type']) ){
            $result = $result->orderBy($filters['order_by_type'], $filters['order_by']);
        }

        return $paginate ? $result->paginate($paginate) : $result->get();
    }

    /**
     * find last event by user id
     * @param string $userId
     * @return WalletEvent|null
     */
    public function findLastEventByUserId(string $userId): WalletEvent|null
    {
        $result = DB::table(self::TABLE_EVENT)
            ->where('user_id', $userId)
            ->orderByDesc('event_count')
            ->first();
        if(! $result){
            return null;
        }

        $result->detail = (array) json_decode($result->detail);
        return WalletEvent::toObject((array) $result);
    }

    /**
     * find snapshot by user id
     * @param string $userId
     * @return Wallet|null
     */
    public function findSnapshotByUserId(string $userId): Wallet|null
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
