<?php

namespace Muchacuba\User\Account;

use Muchacuba\User\Account\Operation\ConnectToStorageInternalWorker as ConnectToOperationStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class LogOperationInternalWorker
{
    /**
     * @var ConnectToOperationStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToOperationStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.user.account.operation.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToOperationStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Logs a creation operation.
     *
     * @param string $uniqueness
     */
    public function logCreation($uniqueness)
    {
        $created = time();

        $this->connectToStorageInternalWorker->connect()->insert(
            array(
                'type' => 'creation',
                'uniqueness' => $uniqueness,
                'year' => date('Y', $created),
                'month' => date('m', $created),
                'day' => date('d', $created)
            )
        );
    }

    /**
     * Logs a deletion operation.
     *
     * @param string $uniqueness
     */
    public function logDeletion($uniqueness)
    {
        $created = time();

        $this->connectToStorageInternalWorker->connect()->insert(
            array(
                'type' => 'deletion',
                'uniqueness' => $uniqueness,
                'year' => date('Y', $created),
                'month' => date('m', $created),
                'day' => date('d', $created)
            )
        );
    }
}
