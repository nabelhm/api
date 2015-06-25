<?php

namespace Muchacuba\Privilege;

use Muchacuba\Privilege\AssignedRole\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class AssignRoleInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.privilege.assigned_role.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Assigns to the given uniqueness, the given role.
     *
     * @param string $uniqueness
     * @param string $role
     */
    public function assign($uniqueness, $role)
    {
        $this->connectToStorageInternalWorker->connect()->insert([
            'uniqueness' => $uniqueness,
            'role' => $role
        ]);
    }

}