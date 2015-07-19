<?php

namespace Muchacuba\InfoSms\Message;
    
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Message\Stat\ConnectToStorageInternalWorker as ConnectToStatStorageInternalWorker;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectStatsTestWorker
{
    /**
     * @var ConnectToStatStorageInternalWorker
     */
    private $connectToStatStorageInternalWorker;

    /**
     * @param ConnectToStatStorageInternalWorker $connectToStatStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStatStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.message.stat.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(
        ConnectToStatStorageInternalWorker $connectToStatStorageInternalWorker
    )
    {
        $this->connectToStatStorageInternalWorker = $connectToStatStorageInternalWorker;

    }

    /**
     * Collects stats.
     *
     * @return \Iterator
     */
    public function collect()
    {
        $cursor = $this->connectToStatStorageInternalWorker->connect()
            ->find()
            ->fields([
                '_id' => 0 // Exclude
            ])
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
