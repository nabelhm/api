<?php

namespace Muchacuba\InfoSms\Message\Link;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonExistentMessageInternalException extends \Exception
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct(sprintf(
            "The link for message \"%s\" should exist but it was not found.",
            $message
        ));
    }
}
