<?php

namespace Cubalider\Sms\Silverstreet;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DeliveryOperation implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $created;

    /**
     * @param string $id
     * @param string $message
     * @param string $status
     * @param int    $created
     */
    function __construct($id, $message, $status, $created)
    {
        $this->id = $id;
        $this->message = $message;
        $this->status = $status;
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'status' => $this->status,
            'created' => $this->created
        ];
    }
}
