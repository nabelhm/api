<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\Operation\ConnectToStorageInternalWorker as ConnectToOperationStorageInternalWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ComputeOperationsFromTopicBetweenDatesApiWorker
{
    const GROUP_BY_DAY = 1;
    const GROUP_BY_MONTH = 2;
    const GROUP_BY_YEAR = 3;

    /**
     * @var ConnectToOperationStorageInternalWorker
     */
    private $connectToOperationStorageInternalWorker;

    /**
     * @param ConnectToOperationStorageInternalWorker $connectToOperationStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToOperationStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.operation.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToOperationStorageInternalWorker $connectToOperationStorageInternalWorker)
    {
        $this->connectToOperationStorageInternalWorker = $connectToOperationStorageInternalWorker;
    }

    /**
     * @param string  $topic
     * @param string  $from
     * @param string  $to
     * @param int     $group
     *
     * @return int[]
     *
     * @throws InvalidGroupApiException
     */
    public function compute($topic, $from, $to, $group)
    {
        switch ($group) {
            case self::GROUP_BY_DAY:
                $timestamp = [
                    'year' => ['$year' => '$timestamp'],
                    'month' => ['$month' => '$timestamp'],
                    'day' => ['$dayOfMonth' => '$timestamp']
                ];

                break;
            case self::GROUP_BY_MONTH:
                $timestamp = [
                    'year' => ['$year' => '$timestamp'],
                    'month' => ['$month' => '$timestamp']
                ];

                break;
            case self::GROUP_BY_YEAR:
                $timestamp = [
                    'year' => ['$year' => '$timestamp']
                ];

                break;
            default:
                throw new InvalidGroupApiException();
        }

        $response = $this->connectToOperationStorageInternalWorker->connect()
            ->aggregate(
                [
                    ['$match' => [
                        'topics' => $topic,
                        'timestamp' => [
                            '$gte' => new \MongoDate($from),
                            '$lte' => new \MongoDate($to),
                        ]
                    ]],
                    ['$group' => [
                        '_id' => [
                            'type' => '$type',
                            'timestamp' => $timestamp
                        ],
                        'total' => [
                            '$sum' => 1
                        ]
                    ]],
                    ['$sort' => ['_id' => 1]]
                ]
            );

        $stats = [];
        foreach ($response['result'] as $item) {
            switch ($group) {
                case self::GROUP_BY_DAY:
                    $stats[] = [
                        'total' => $item['total'],
                        'type' => $item['_id']['type'],
                        'year' => $item['_id']['timestamp']['year'],
                        'month' => $item['_id']['timestamp']['month'],
                        'day' => $item['_id']['timestamp']['day']
                    ];

                    break;
                case self::GROUP_BY_MONTH:
                    $stats[] = [
                        'total' => $item['total'],
                        'type' => $item['_id']['type'],
                        'year' => $item['_id']['timestamp']['year'],
                        'month' => $item['_id']['timestamp']['month']
                    ];

                    break;
                case self::GROUP_BY_YEAR:
                    $stats[] = [
                        'total' => $item['total'],
                        'type' => $item['_id']['type'],
                        'year' => $item['_id']['timestamp']['year']
                    ];

                    break;
                default:
                    throw new InvalidGroupApiException();
            }
        }

        return $stats;
    }
}
