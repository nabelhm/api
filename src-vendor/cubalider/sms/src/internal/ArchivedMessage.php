<?php

namespace Cubalider\Sms;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ArchivedMessage implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $receiver;

    /**
     * @var string
     */
    private $body;

    /**
     * @var int
     */
    private $created;

    /**
     * @param string $id
     * @param string $receiver
     * @param string $body
     * @param int    $created
     */
    function __construct($id, $receiver, $body, $created)
    {
        $this->id = $id;
        $this->receiver = $receiver;
        $this->body = $body;
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
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
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
            'receiver' => $this->receiver,
            'body' => $this->body,
            'created' => $this->created
        ];
    }
}
