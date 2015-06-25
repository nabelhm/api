<?php

namespace Muchacuba\RechargeCard\Card;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
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
            "The card with code \"%s\" should exist but it was not found.",
            $code
        ));
    }
}