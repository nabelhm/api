<?php

namespace Muchacuba\InfoSms\Message;

use Muchacuba\InfoSms\Message\Link\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateLinkInternalWorker
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
     * Creates a link between given message and given info.
     *
     * @param string $message
     * @param string $info
     * @param string $subscription
     */
    public function create($message, $info, $subscription)
    {
        $this->connectToStorageInternalWorker->connect()->insert([
            'message' => $message,
            'info' => $info,
            'subscription' => $subscription
        ]);
    }
}
