<?php

namespace Muchacuba\Privilege;

use Muchacuba\Privilege\AssignedRole\ConnectToStorageInternalWorker as ConnectToAssignedRoleStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickProfileApiWorker
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
     * Picks the profile with given uniqueness.
     *
     * @param string $uniqueness
     *
     * @return array An array with keys uniqueness and roles
     */
    public function pick($uniqueness)
    {
        $assignedRoles = $this->connectToAssignedRoleStorageInternalWorker->connect()
            ->find(
                [
                    'uniqueness' => $uniqueness
                ]
            )
            ->fields(
                [
                    '_id' => 0, // Exclude
                    'role' => 1
                ]
            );

        $roles = [];
        foreach ($assignedRoles as $assignedRole) {
            $roles[] = $assignedRole['role'];
        }

        return [
            'uniqueness' => $uniqueness,
            'roles' => $roles
        ];
    }

}