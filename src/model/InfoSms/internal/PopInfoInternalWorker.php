<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Info\ConnectToStorageInternalWorker as ConnectToInfoStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Info\EmptyQueueInternalException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PopInfoInternalWorker
{
    /**
     * @var ConnectToInfoStorageInternalWorker
     */
    private $connectToInfoStorageInternalWorker;

    /**
     * @param ConnectToInfoStorageInternalWorker     $connectToInfoStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToInfoStorageInternalWorker"     = @Di\Inject("muchacuba.info_sms.info.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(
        ConnectToInfoStorageInternalWorker $connectToInfoStorageInternalWorker
    ) {
        $this->connectToInfoStorageInternalWorker = $connectToInfoStorageInternalWorker;
    }

    /**
     * Pops oldest info. 
     *
     * @return array The info as an array with the following keys:
     *               id, body, topics and created
     *
     * @throws EmptyQueueInternalException
     */
    public function pop()
    {
        $item = $this->connectToInfoStorageInternalWorker->connect()
            ->find()
            ->sort([
                'created' => 1
            ])
            ->limit(1)
            ->getNext();

        if (!$item) {
            throw new EmptyQueueInternalException();
        }

        $this->connectToInfoStorageInternalWorker->connect()->remove([
            'id' => $item['id'],
        ]);

        return $item;
    }
}
