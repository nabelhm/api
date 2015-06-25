<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Info\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateInfoTestWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.info.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    ) {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates an info.
     *
     * @param string   $id
     * @param string   $body
     * @param string[] $topics
     */
    public function create($id, $body, $topics)
    {
        $this->connectToStorageInternalWorker->connect()->insert(
            array(
                'id' => $id,
                'body' => $body,
                'topics' => $topics,
                'created' => time()
            )
        );

        sleep(1);
    }
}
