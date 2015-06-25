<?php

namespace Cubalider\Sms;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UnknownStatusInternalException extends \Exception
{
    public function __construct($status)
    {
        parent::__construct(sprintf("The status \"%s\" is unknown", $status));
    }
}
