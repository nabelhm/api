<?php

namespace Muchacuba\Credit;

use Muchacuba\Credit\Profile\ConnectToStorageInternalWorker;
use Muchacuba\Credit\Profile\Balance\Operation\ConnectToStorageInternalWorker as ConnectToBalanceOperationStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class DeleteProfileSharedWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var ConnectToBalanceOperationStorageInternalWorker
     */
    private $connectToBalanceOperationStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker                 $connectToStorageInternalWorker
     * @param ConnectToBalanceOperationStorageInternalWorker $connectToBalanceOperationStorageInternalWorker
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        ConnectToBalanceOperationStorageInternalWorker $connectToBalanceOperationStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->connectToBalanceOperationStorageInternalWorker = $connectToBalanceOperationStorageInternalWorker;
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

        $this->connectToBalanceOperationStorageInternalWorker->connect()->remove([
            'uniqueness' => $uniqueness
        ]);
    }
}