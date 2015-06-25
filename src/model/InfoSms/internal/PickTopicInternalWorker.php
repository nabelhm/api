<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Topic\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Topic\NonExistentIdApiException;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Topic\NonExistentIdInternalException;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickTopicInternalWorker
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
     * Picks the topic with given id.
     *
     * @param string $id
     *
     * @return array A topic as an array with the following keys:
     *               id, title, description and average.
     *
     * @throws NonExistentIdInternalException
     */
    public function pick($id)
    {
        $topic = $this->connectToStorageInternalWorker->connect()
            ->findOne([
                'id' => $id
            ]);

        if (!$topic) {
            throw new NonExistentIdInternalException($id);
        }

        return $topic;
    }
}
