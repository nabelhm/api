<?php

namespace Muchacuba\InfoSms\Message;

use Muchacuba\InfoSms\Message\Stat\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectLatestStatsApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.message.stat.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Collects latest stats.
     *
     * @param int|null $amount
     *
     * @return \Iterator An array of stats with the following keys:
     *                   id, year, month, day, body, topics, created, total and delivered
     */
    public function collect($amount = 10)
    {
        $cursor = $this->connectToStorageInternalWorker->connect()
            ->find()
            ->fields([
                '_id' => 0 // Exclude
            ])
            ->limit($amount)
            ->sort([
                '_id' => -1
            ]);

        $stats = [];
        foreach ($cursor as $i => $stat) {
            /** @var \MongoDate $timestamp */
            $timestamp = $stat['timestamp'];
            $stat['timestamp'] = $timestamp->sec;

            $stats[] = $stat;
        }

        return new \ArrayIterator($stats);
    }
}
