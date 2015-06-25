<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Profile\ConnectToStorageInternalWorker;
use Muchacuba\RechargeCard\Profile\NonExistentUniquenessSharedException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 */
class DeleteProfileSharedWorker
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
     * Deletes the profile with given uniqueness.
     *
     * @param string $uniqueness
     *
     * @throws NonExistentUniquenessSharedException
     */
    public function delete($uniqueness)
    {
        $result = $this->connectToStorageInternalWorker->connect()->remove([
            'uniqueness' => $uniqueness
        ]);

        if ($result['n'] == 0) {
            throw new NonExistentUniquenessSharedException($uniqueness);
        }
    }
}