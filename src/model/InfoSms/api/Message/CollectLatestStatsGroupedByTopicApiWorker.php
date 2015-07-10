<?php

namespace Muchacuba\InfoSms\Message;

use Muchacuba\InfoSms\CollectTopicsApiWorker;
use Muchacuba\InfoSms\Message\Stat\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectLatestStatsGroupedByTopicApiWorker
{
    /**
     * @var CollectTopicsApiWorker
     */
    private $collectTopicsApiWorker;

    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param CollectTopicsApiWorker         $collectTopicsApiWorker
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "collectTopicsApiWorker"         = @Di\Inject("muchacuba.info_sms.collect_topics_api_worker"),
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.message.stat.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(
        CollectTopicsApiWorker $collectTopicsApiWorker,
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->collectTopicsApiWorker = $collectTopicsApiWorker;
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Collects latest stats grouped by topic.
     */
    public function collect()
    {
        $topics = $this->collectTopicsApiWorker->collect();

        $stats = [];
        foreach ($topics as $topic) {
            $stats = array_merge(
                $stats,
                iterator_to_array($this->connectToStorageInternalWorker->connect()
                    ->find([
                        'topics' => $topic['id']
                    ])
                    ->fields([
                        '_id' => 0 // Exclude
                    ])
                    ->sort([
                        '_id' => -1
                    ])
                    ->limit(5)
                )
            );
        }

        return $stats;
    }
}
