<?php

namespace Muchacuba\InfoSms\Info;

use Muchacuba\InfoSms\Info\ByTopicStat\ConnectToStorageInternalWorker as ConnectToByTopicStatStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectByTopicStatsFromCurrentYearApiWorker
{
//    /**
//     * @var ConnectToByTopicStatStorageInternalWorker
//     */
//    private $connectToByTopicStatStorageInternalWorker;
//
//    /**
//     * @param ConnectToByTopicStatStorageInternalWorker $connectToByTopicStatStorageInternalWorker
//     *
//     * @Di\InjectParams({
//     *     "connectToByTopicStatStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.info.by_topic_stat.connect_to_storage_internal_worker")
//     * })
//     */
//    public function __construct(ConnectToByTopicStatStorageInternalWorker $connectToByTopicStatStorageInternalWorker)
//    {
//        $this->connectToByTopicStatStorageInternalWorker = $connectToByTopicStatStorageInternalWorker;
//    }
//
//    /**
//     * @param string $topic
//     *
//     * @return int[]
//     */
//    public function collect($topic)
//    {
//        $initialMonth = '01';
//        $finalMonth = date('m');
//
//        $response = $this->connectToByTopicStatStorageInternalWorker->connect()
//            ->aggregate(
//                [
//                    ['$match' => [
//                        'topic' => $topic,
//                        'year' => date('Y'),
//                        'month' => [
//                            '$gte' => (string) $initialMonth,
//                            '$lte' => (string) $finalMonth
//                        ]
//                    ]],
//                    ['$group' => [
//                        '_id' => [
//                            'month' => '$month'
//                        ],
//                        'total' => [
//                            '$sum' => '$amount'
//                        ]
//                    ]]
//                ]
//            );
//
//        $stats = [];
//        for ($i = $initialMonth; $i <= $finalMonth; $i++) {
//            $found = false;
//
//            foreach ($response['result'] as $item) {
//                if ($i == (int) $item['_id']['month']) {
//                    $stats[] = $item['total'];
//
//                    $found = true;
//
//                    break;
//                }
//            }
//
//            if (!$found) {
//                $stats[] = 0;
//            }
//        }
//
//        return $stats;
//    }
}
