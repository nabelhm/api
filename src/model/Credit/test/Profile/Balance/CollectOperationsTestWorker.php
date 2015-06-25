<?php

namespace Muchacuba\Credit\Profile\Balance;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\Credit\Profile\Balance\Operation\ConnectToStorageInternalWorker;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectOperationsTestWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.credit.profile.balance.operation.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;

    }

    /**
     * Collects operations.
     *
     * @return \Iterator
     */
    public function collect()
    {
        return $this->connectToStorageInternalWorker->connect()
            ->find()
            ->fields([
                '_id' => 0 // Exclude
            ]);
    }
}
