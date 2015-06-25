<?php

namespace Cubalider\Unique;

use Cubalider\Unique\Uniqueness\ConnectToStorageInternalWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ComputeUniquenessesSharedWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     */
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Computes uniquenesses.
     *
     * @return int
     */
    public function compute()
    {
        return $this->connectToStorageInternalWorker
            ->connect()
            ->find()
            ->count();
    }
}