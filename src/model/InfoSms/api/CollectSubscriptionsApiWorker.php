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
class CollectSubscriptionsApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;

    }

    /**
     * Collects subscriptions from given uniqueness.
     *
     * @param string $uniqueness
     *
     * @return \Iterator An array of subscriptions with the following keys:
     *                   mobile, uniqueness, alias, topics, balance and active
     */
    public function collect($uniqueness)
    {
        return $this->connectToStorageInternalWorker->connect()
            ->find([
                'uniqueness' => $uniqueness
            ])
            ->fields([
                '_id' => 0,  // Exclude
                'order' => 0 // Exclude
            ])
            ->sort([
                'order' => -1 // Descending
            ]);
    }
}
