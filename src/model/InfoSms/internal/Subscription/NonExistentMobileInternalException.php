<?php

namespace Muchacuba\InfoSms\Subscription;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonExistentMobileInternalException extends \Exception
{
    /**
     * @param string $mobile
     */
    public function __construct($mobile)
    {
        parent::__construct(sprintf(
            "The subscription for mobile \"%s\" should exist but it was not found.",
            $mobile
        ));
    }
}
