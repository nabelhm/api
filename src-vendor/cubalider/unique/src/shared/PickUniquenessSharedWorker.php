<?php

namespace Cubalider\Unique;

use Cubalider\Unique\Uniqueness\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickUniquenessSharedWorker
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
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Picks the uniqueness with given id.
     *
     * @param string $id
     *
     * @return array An array with the following keys:
     *               id
     *
     * @throws NonExistentIdSharedException
     */
    public function pick($id)
    {
        $uniqueness = $this->connectToStorageInternalWorker->connect()->findOne(array(
            'id' => $id
        ));

        if (!$uniqueness) {
            throw new NonExistentIdSharedException($id);
        }

        return $uniqueness;
    }

}