<?php

namespace Muchacuba\Privilege;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class CreateProfileSharedWorker
{
    /**
     * @var AssignRoleInternalWorker
     */
    private $assignRoleInternalWorker;

    /**
     * @param AssignRoleInternalWorker $assignRoleInternalWorker
     *
     * @Di\InjectParams({
     *     "assignRoleInternalWorker" = @Di\Inject("muchacuba.privilege.assign_role_internal_worker")
     * })
     */
    function __construct(
        AssignRoleInternalWorker $assignRoleInternalWorker
    )
    {
        $this->assignRoleInternalWorker = $assignRoleInternalWorker;
    }

    /**
     * Creates a profile.
     *
     * @param string   $uniqueness
     * @param string[] $roles
     */
    public function create($uniqueness, $roles)
    {
        foreach ($roles as $role) {
            $this->assignRoleInternalWorker->assign($uniqueness, $role);
        }
    }
}