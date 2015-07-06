<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Topic\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateTopicInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.topic.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates a topic.
     *
     * @param string $id
     * @param string $title
     * @param string $description
     * @param int    $average
     * @param int    $order
     */
    public function create($id, $title, $description, $average, $order)
    {
        $this->connectToStorageInternalWorker->connect()->insert(array(
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'average' => $average,
            'active' => true,
            'order' => (int) $order
        ));
    }
}
