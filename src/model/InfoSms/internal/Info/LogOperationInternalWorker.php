<?php

namespace Muchacuba\InfoSms\Info;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\Operation\ConnectToStorageInternalWorker as ConnectToOperationStorageInternalWorker;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class LogOperationInternalWorker
{
    /**
     * @var ConnectToOperationStorageInternalWorker
     */
    private $connectToOperationStorageInternalWorker;

    /**
     * @param ConnectToOperationStorageInternalWorker $connectToOperationStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToOperationStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.info.operation.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToOperationStorageInternalWorker $connectToOperationStorageInternalWorker)
    {
        $this->connectToOperationStorageInternalWorker = $connectToOperationStorageInternalWorker;
    }

    /**
     * Logs a create operation.
     *
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string[] $topics
     * @param int      $amount
     */
    public function logCreate($mobile, $uniqueness, $topics, $amount)
    {
        $today = time();
        $year = date('Y', $today);
        $month = date('m', $today);
        $day = date('d', $today);

        $this->connectToOperationStorageInternalWorker->connect()->insert([
            'type' => 1,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'mobile' => $mobile,
            'uniqueness' => $uniqueness,
            'topics' => $topics,
            'amount' => $amount
        ]);
    }
}
