<?php

namespace Muchacuba\InfoSms\Subscription;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonExistentMobileAndUniquenessInternalException extends \Exception
{
    /**
     * @param string $mobile
     * @param string $uniqueness
     */
    public function __construct($mobile, $uniqueness)
    {
        parent::__construct(sprintf(
            "The subscription for mobile \"%s\" and uniqueness \"%s\" should exist but it was not found.",
            $mobile,
            $uniqueness
        ));
    }
}
