<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Topic\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Info\ConnectToStorageInternalWorker as ConnectToInfoStorageInternalWorker;
use Muchacuba\InfoSms\Topic\NonExistentIdApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DeleteTopicApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var ConnectToInfoStorageInternalWorker
     */
    private $connectToInfoStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker     $connectToStorageInternalWorker
     * @param ConnectToInfoStorageInternalWorker $connectToInfoStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker"     = @Di\Inject("muchacuba.info_sms.topic.connect_to_storage_internal_worker"),
     *     "connectToInfoStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.info.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        ConnectToInfoStorageInternalWorker $connectToInfoStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->connectToInfoStorageInternalWorker = $connectToInfoStorageInternalWorker;
    }

    /**
     * Deletes the topic with given id.
     *
     * @param string $id
     *
     * @throws NonExistentIdApiException
     */
    public function delete($id)
    {
        $result = $this->connectToStorageInternalWorker->connect()->remove([
            'id' => $id
        ]);

        if ($result['n'] == 0) {
            throw new NonExistentIdApiException();
        }

        $this->connectToInfoStorageInternalWorker->connect()->remove([
            'topics' => $id
        ]);
    }
}
