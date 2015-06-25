<?php

namespace Muchacuba\Privilege;

use Muchacuba\Privilege\AssignedRole\ConnectToStorageInternalWorker as ConnectToAssignedRoleStorageInternalWorker;
use Muchacuba\Privilege\Invitation\Profile\NonExistentUniquenessAndRoleInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class RevokeRoleInternalWorker
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
     * Revokes to the given uniqueness, the given role.
     *
     * @param string $uniqueness
     * @param string $role
     *
     * @throws NonExistentUniquenessAndRoleInternalException
     */
    public function revoke($uniqueness, $role)
    {
        $result = $this->connectToAssignedRoleStorageInternalWorker->connect()->remove([
            'uniqueness' => $uniqueness,
            'role' => $role
        ]);

        if ($result['n'] == 0) {
            throw new NonExistentUniquenessAndRoleInternalException($uniqueness, $role);
        }
    }

}