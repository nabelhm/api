<?php

namespace Muchacuba\Privilege\Invitation\Profile;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonExistentUniquenessAndRoleInternalException extends \Exception
{
    /**
     * @param string $uniqueness
     * @param string $role
     */
    public function __construct($uniqueness, $role)
    {
        parent::__construct(sprintf(
            "The assignation to uniqueness \"%s\" of role with \"%s\" must exist but it was not found.",
            $role,
            $uniqueness
        ));
    }
}