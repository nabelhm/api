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
class DeleteLinkInternalWorker
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
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Deletes the link for given message.
     *
     * @param string $message
     *
     * @throws NonExistentMessageInternalException
     */
    public function delete($message)
    {
        $result = $this->connectToStorageInternalWorker->connect()->remove([
            'message' => $message
        ]);

        if ($result['n'] == 0) {
            throw new NonExistentMessageInternalException($message);
        }
    }
}
