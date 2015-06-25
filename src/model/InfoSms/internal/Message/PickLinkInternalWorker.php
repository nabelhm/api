<?php

namespace Muchacuba\InfoSms\Message;

use Muchacuba\InfoSms\Message\Link\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Message\Link\NonExistentMessageInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickLinkInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.message.link.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Picks the link for given message.
     *
     * @param string $message
     *
     * @return array An array with the following keys:
     *               message and info.
     *
     * @throws NonExistentMessageInternalException
     */
    public function pick($message)
    {
        $link = $this->connectToStorageInternalWorker->connect()
            ->findOne([
                'message' => $message
            ]);

        if (!$link) {
            throw new NonExistentMessageInternalException($message);
        }

        return $link;
    }
}
