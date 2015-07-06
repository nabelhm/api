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
class UpdateTopicApiWorker
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
     * Updates a topic.
     *
     * @param string  $id
     * @param string  $title
     * @param string  $description
     * @param int     $average
     * @param boolean $active
     * @param int     $order
     *
     * @throws NonExistentIdApiException
     */
    public function update($id, $title, $description, $average, $active, $order)
    {
        $result = $this->connectToStorageInternalWorker->connect()->update(
            array('id' => $id),
            array('$set' => array(
                'title' => $title,
                'description' => $description,
                'average' => $average,
                'active' => $active,
                'order' => (int) $order
            ))
        );

        if ($result['n'] == 0) {
            throw new NonExistentIdApiException();
        }
    }
}
