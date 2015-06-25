<?php

namespace Cubalider\Unique;

use Cubalider\Unique\Uniqueness\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DeleteUniquenessSharedWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("cubalider.unique.uniqueness.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Deletes the uniqueness with given id.
     *
     * @param mixed $id
     *
     * @throws NonExistentIdSharedException
     */
    public function delete($id)
    {
        $result = $this->connectToStorageInternalWorker->connect()->remove([
            'id' => $id
        ]);

        if (0 == $result['n']) {
            throw new NonExistentIdSharedException();
        }
    }
}