<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\Operation\ConnectToStorageInternalWorker as ConnectToOperationStorageInternalWorker;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CheckOperationInternalWorker
{
    /**
     * @var ConnectToOperationStorageInternalWorker
     */
    private $connectToOperationStorageInternalWorker;

    /**
     * @param ConnectToOperationStorageInternalWorker $connectToOperationStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToOperationStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.operation.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToOperationStorageInternalWorker $connectToOperationStorageInternalWorker)
    {
        $this->connectToOperationStorageInternalWorker = $connectToOperationStorageInternalWorker;
    }

    /**
     * Checks a trial operation.
     *
     * @param string $mobile
     *
     * @return boolean
     */
    public function checkTrial($mobile)
    {
        $operation = $this->connectToOperationStorageInternalWorker->connect()
            ->findOne([
                'type' => 0,
                'mobile' => $mobile,
            ]);

        return $operation ? true : false;
    }

    /**
     * Checks a create operation.
     *
     * @param string $mobile
     *
     * @return boolean
     */
    public function checkCreate($mobile)
    {
        $operation = $this->connectToOperationStorageInternalWorker->connect()
            ->findOne([
                'type' => 1,
                'mobile' => $mobile,
            ]);

        return $operation ? true : false;
    }
}
