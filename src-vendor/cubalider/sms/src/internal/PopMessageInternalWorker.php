<?php

namespace Cubalider\Sms;

use Cubalider\Sms\Message\ConnectToStorageInternalWorker;
use Cubalider\Sms\Message\EmptyQueueInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PopMessageInternalWorker
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
     * Pops a message.
     *
     * @return array An array with the following keys:
     *               _id, receiver and body
     *
     * @throws EmptyQueueInternalException
     */
    public function pop()
    {
        $item = $this->connectToStorageInternalWorker->connect()
            ->find()
            ->sort([
                '_id' => 1
            ])
            ->limit(1)
            ->getNext();

        if (!$item) {
            throw new EmptyQueueInternalException();
        }

        $this->connectToStorageInternalWorker->connect()->remove(array(
            '_id' => $item['_id'],
        ));

        return $item;
    }
}
