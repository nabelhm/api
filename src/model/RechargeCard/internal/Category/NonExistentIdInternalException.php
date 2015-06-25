<?php

namespace Muchacuba\RechargeCard\Category;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonExistentIdInternalException extends \Exception
{
    /**
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct(sprintf(
            "The category with id \"%s\" should exist but it was not found.",
            $id
        ));
    }
}