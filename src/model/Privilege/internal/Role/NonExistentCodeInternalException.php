<?php

namespace Muchacuba\Privilege\Role;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonExistentCodeInternalException extends \Exception
{
    /**
     * @param string $code
     */
    public function __construct($code)
    {
        parent::__construct(sprintf(
            "The role with code \"%s\" should exist but it was not found.",
            $code
        ));
    }
}