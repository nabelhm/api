<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Topic\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectTopicsApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.topic.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Collects topics.
     *
     * @return \Iterator An array of topics with the following keys:
     *                   id, title, description, average, active and order.
     */
    public function collect()
    {
        return $this->connectToStorageInternalWorker->connect()
            ->find()
            ->fields([
                '_id' => 0
            ])
            ->sort([
                'active' => -1,
                'order' => 1
            ]);
    }
}
