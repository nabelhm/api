<?php

namespace Cubalider\Sms;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DeliveryEvent extends Event
{
    /**
     * @var string
     */
    private $message;

    /**
     * @param string $message
     */
    function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
