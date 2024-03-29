<?php
namespace D3cr33\Wallet\Core\Events;

use D3cr33\Wallet\Core\Events\Contracts\WalletEventInterface;
use D3cr33\Wallet\Core\Exceptions\WalletEventException;
use Exception;
use Illuminate\Support\Str;

class WalletEvent
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
     * store detail of events
     * @var array
     */
    public array $detail;

    /**
     * create new instance of wallet event
     * @param string $uuid
     * @param string $userId
     * @param int $amount
     * @param int $eventCount
     * @param string $createdAt
     */
    private function __construct(
        string $uuid,
        string $userId,
        int $amount,
        int $eventCount,
        string $createdAt,
        array $detail
    )
    {
        $this->uuid = $uuid;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->eventCount = $eventCount;
        $this->detail = $detail;
        $this->createdAt = $createdAt;
    }

    /**
     * initialize wallet event
     * @param string $userId
     * @param int $amount
     * @param int $eventCount
     * @param string $createdAt
     */
    public static function initialize(
        string $userId,
        int $amount,
        int $eventCount,
        string $createdAt,
        array $detail
    ){
        return new static(
            Str::uuid(),
            $userId,
            $amount,
            $eventCount,
            $createdAt,
            $detail
        );
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
            'detail'    =>  $this->detail,
            'created_at'    =>  $this->createdAt,
        ];
    }

    /**
     * convert to wallet event object
     * @param array $event
     * @return WalletEvent
     */
    public static function toObject(array $event): WalletEvent
    {
        try{
            $namespace = null;
            switch($event['event_type']){
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
                $event['uuid'],
                $event['user_id'],
                $event['amount'],
                $event['event_count'],
                $event['created_at'],
                $event['detail'],
            );
        }catch(Exception $e){
            throw new WalletEventException($e);
        }
    }
}
