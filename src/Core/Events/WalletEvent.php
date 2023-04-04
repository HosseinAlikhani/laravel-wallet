<?php
namespace D3cr33\Wallet\Core\Events;

use D3cr33\Wallet\Core\Events\Contracts\WalletEventInterface;
use Exception;
use Illuminate\Support\Str;

abstract class WalletEvent implements WalletEventInterface
{
    /**
     * event uuid - unique id
     * @var string
     */
    public string $uuid;

    /**
     * event raise on which user authenticate id
     * @var string
     */
    public string $userId;

    /**
     * amount of event
     * @var int
     */
    public int $amount;

    /**
     * count of event that applied to aggregate
     * @var int
     */
    public int $eventCount;

    /**
     * dateTime when event raised
     * @var string
     */
    public string $createdAt;

    /**
     * create new instance of wallet event
     * @param string $uuid
     * @param string $userId
     * @param int $amount
     * @param int $eventCount
     */
    public function __construct(
        string $userId,
        int $amount,
        int $eventCount,
        string $createdAt
    )
    {
        $this->uuid = Str::uuid();
        $this->userId = $userId;
        $this->amount = $amount;
        $this->eventCount = $eventCount;
        $this->createdAt = $createdAt;
    }

    /**
     * to array wallet event
     * @return array
     */
    public function toArray(): array
    {
        return [
            'uuid'  =>  $this->uuid,
            'user_id'   =>  $this->userId,
            'amount'    =>  $this->amount,
            'event_type'   =>  $this->getEventType(),
            'event_count'   =>  $this->eventCount,
            'created_at'    =>  $this->createdAt
        ];
    }

    /**
     * convert to wallet event object
     * @param object $event
     * @return WalletEvent
     */
    public static function toObject(object $event): WalletEvent
    {
        $namespace = null;
        switch($event->event_type){
            case IncreaseWalletEvent::EVENT_TYPE: 
                $namespace = IncreaseWalletEvent::class;
                break;
            case DecreaseWalletEvent::EVENT_TYPE:
                $namespace = DecreaseWalletEvent::class;
                break;
            default:
                throw new Exception(trans('wallet::messages.wallet_record_not_valid'));
        }

        return new $namespace(
            $event->user_id,
            $event->amount,
            $event->event_count,
            $event->created_at
        );
    }
}
