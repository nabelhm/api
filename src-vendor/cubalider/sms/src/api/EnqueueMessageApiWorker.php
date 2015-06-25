<?php

namespace Cubalider\Sms;

use Cubalider\Sms\Message\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class EnqueueMessageApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("cubalider.sms.message.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    ) {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates a message.
     *
     * @param string $receiver
     * @param string $body
     *
     * @return string The already created message's id.
     */
    public function enqueue($receiver, $body)
    {
        $message = uniqid();

        $this->connectToStorageInternalWorker->connect()->insert(
            array(
                'message' => $message,
                'receiver' => $receiver,
                'body' => $body
            )
        );

        return $message;
    }
}
