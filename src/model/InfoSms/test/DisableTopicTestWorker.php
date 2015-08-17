<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Topic\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Topic\NonExistentIdApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DisableTopicTestWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.topic.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Disables a topic.
     *
     * @param string  $id
     *
     * @throws NonExistentIdApiException
     */
    public function disable($id)
    {
        $result = $this->connectToStorageInternalWorker->connect()->update(
            array('id' => $id),
            array('$set' => array(
                'active' => false
            ))
        );

        if ($result['n'] == 0) {
            throw new NonExistentIdApiException();
        }
    }
}
