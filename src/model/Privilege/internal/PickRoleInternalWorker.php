<?php

namespace Muchacuba\Privilege;

use Muchacuba\Privilege\Role\ConnectToStorageInternalWorker;
use Muchacuba\Privilege\Role\NonExistentCodeInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickRoleInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.privilege.role.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Picks the role with given code.
     *
     * @param string $code
     *
     * @return array
     *
     * @throws NonExistentCodeInternalException
     */
    public function pick($code)
    {
        $role = $this->connectToStorageInternalWorker->findOne($code);

        if (!$role) {
            throw new NonExistentCodeInternalException($code);
        }

        return $role;
    }
}
