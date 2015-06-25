<?php

namespace Muchacuba\InfoSms\Profile;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonExistentUniquenessInternalException extends \Exception
{
    /**
     * @param string $uniqueness
     */
    public function __construct($uniqueness)
    {
        parent::__construct(sprintf(
            "The profile for uniqueness \"%s\" should exist but it was not found.",
            $uniqueness
        ));
    }
}
