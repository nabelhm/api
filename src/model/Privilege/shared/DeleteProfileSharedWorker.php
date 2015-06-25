<?php

namespace Muchacuba\Privilege;

use Muchacuba\Privilege\AssignedRole\ConnectToStorageInternalWorker as ConnectToAssignedRoleStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DeleteProfileSharedWorker
{
    /**
     * @var ConnectToAssignedRoleStorageInternalWorker
     */
    private $connectToAssignedRoleStorageInternalWorker;

    /**
     * @param ConnectToAssignedRoleStorageInternalWorker $connectToAssignedRoleStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToAssignedRoleStorageInternalWorker" = @Di\Inject("muchacuba.privilege.assigned_role.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToAssignedRoleStorageInternalWorker $connectToAssignedRoleStorageInternalWorker
    )
    {
        $this->connectToAssignedRoleStorageInternalWorker = $connectToAssignedRoleStorageInternalWorker;
    }

    /**
     * Deletes the assigned roles and assigned invitation cards for given uniqueness.
     *
     * @param string $uniqueness
     */
    public function delete($uniqueness)
    {
        $this->connectToAssignedRoleStorageInternalWorker->connect()->remove([
            'uniqueness' => $uniqueness
        ]);
    }
}