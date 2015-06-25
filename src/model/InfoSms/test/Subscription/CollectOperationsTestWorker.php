<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\Operation\ConnectToStorageInternalWorker as ConnectToOperationStorageInternalWorker;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectOperationsTestWorker
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
    public function __construct(
        ConnectToOperationStorageInternalWorker $connectToOperationStorageInternalWorker
    )
    {
        $this->connectToOperationStorageInternalWorker = $connectToOperationStorageInternalWorker;

    }

    /**
     * Collects operations.
     *
     * @return \Iterator
     */
    public function collect()
    {
        return $this->connectToOperationStorageInternalWorker->connect()
            ->find()
            ->fields([
                '_id' => 0 // Exclude
            ]);
    }
}
