<?php

namespace Muchacuba\InfoSms\Info;

use Muchacuba\InfoSms\Info\ByTopicStat\ConnectToStorageInternalWorker as ConnectToByTopicStatStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectByTopicStatsFromCurrentWeekApiWorker
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
//        $initialDay = date('d') - date('N');
//        if (count($initialDay) == 1) {
//            $initialDay = sprintf("0%s", $initialDay);
//        }
//        $finalDay = date('d');// + 6 - date('N');
//
//        $response = $this->connectToByTopicStatStorageInternalWorker->connect()
//            ->aggregate(
//                [
//                    ['$match' => [
//                        'topic' => $topic,
//                        'year' => date('Y'),
//                        'month' => date('m'),
//                        'day' => [
//                            '$gte' => (string) $initialDay,
//                            '$lte' => (string) $finalDay
//                        ]
//                    ]],
//                    ['$group' => [
//                        '_id' => [
//                            'day' => '$day'
//                        ],
//                        'total' => [
//                            '$sum' => '$amount'
//                        ]
//                    ]]
//                ]
//            );
//
//        $stats = [];
//        for ($i = $initialDay; $i <= $finalDay; $i++) {
//            $found = false;
//
//            foreach ($response['result'] as $item) {
//                if ($i == (int) $item['_id']['day']) {
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
