<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\Operation\ConnectToStorageInternalWorker as ConnectToOperationStorageInternalWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectOperationsByTopicFromCurrentWeekApiWorker
{
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
     * @param string $topic
     * @param string $initialDay
     * @param string $finalDay
     *
     * @return int[]
     */
    public function collect($topic, $initialDay, $finalDay)
    {
//        $initialDay = (string) (date('d') - date('N'));
//        if (strlen($initialDay) == 1) {
//            $initialDay = sprintf("0%s", $initialDay);
//        }
//        $finalDay = (string) (date('d') + 6 - date('N'));

        $response = $this->connectToOperationStorageInternalWorker->connect()
            ->aggregate(
                [
                    ['$match' => [
                        'topics' => $topic,
                        'year' => date('Y'),
                        'month' => date('m'),
                        'day' => [
                            '$gte' => $initialDay,
                            '$lte' => $finalDay
                        ]
                    ]],
                    ['$group' => [
                        '_id' => [
                            'day' => '$day',
                            'type' => '$type'
                        ],
                        'total' => [
                            '$sum' => 1
                        ]
                    ]]
                ]
            );

        $stats = [];
        $existent = 0;
        for ($i = (int) $initialDay; $i <= (int) $finalDay; $i++) {
            $current = 0;
            foreach ($response['result'] as $item) {
                if ($i == (int) $item['_id']['day']) {
                    if ($item['_id']['type'] == 0 || $item['_id']['type'] == 1) {
                        $current += $item['total'];
                    }

                    if ($item['_id']['type'] == 3) {
                        $current -= $item['total'];
                    }
                }
            }

            $stats[] += $existent + $current;
            $existent += $current;
        }

        return $stats;
    }
}
