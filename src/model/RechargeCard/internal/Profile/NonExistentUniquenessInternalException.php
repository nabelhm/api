<?php

namespace Muchacuba\RechargeCard\Profile;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonExistentUniquenessInternalException extends \LogicException
{
    /**
     * @param string $uniqueness
     */
    public function __construct($uniqueness)
    {
        parent::__construct(sprintf(
            "The profile with uniqueness \"%s\" should exist but it was not found.",
            $uniqueness
        ));
    }
}
