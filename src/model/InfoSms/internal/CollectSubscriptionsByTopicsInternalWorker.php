<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Subscription\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectSubscriptionsByTopicsInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;

    }

    /**
     * Collects subscriptions by given topics.
     *
     * @param string[] $topics
     *
     * @return \Iterator An array of subscriptions with the following keys:
     *                   mobile, uniqueness, alias, topics, balance and active
     */
    public function collect($topics)
    {
        $query = [];
        foreach ($topics as $topic) {
            $query[] = ['topics' => $topic];
        }

        return $this->connectToStorageInternalWorker->connect()
            ->find([
                '$or' => $query
            ])
            ->fields([
                '_id' => 0
            ])
            ->sort([
                'order' => -1 // Descending
            ]);
    }
}
