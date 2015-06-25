<?php

namespace Cubalider\Sms;

use Cubalider\Sms\Message;
use Cubalider\Sms\DeliveryOperation\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class LogDeliveryOperationApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("cubalider.sms.delivery_operation.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    ) {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Logs a delivery operation.
     *
     * @param string   $id
     * @param string   $status
     * @param int|null $created
     */
    public function log($id, $status, $created)
    {
        $this->connectToStorageInternalWorker->connect()->insert(
            array(
                'message' => $id,
                'status' => $status,
                'created' => $created
            )
        );
    }
}
