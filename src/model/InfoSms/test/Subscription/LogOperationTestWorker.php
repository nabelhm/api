<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * 
 * @Di\Service()
 */
class LogOperationTestWorker
{
    /**
     * @var LogOperationInternalWorker
     */
    private $logOperationInternalWorker;

    /**
     * @param LogOperationInternalWorker $logOperationInternalWorker
     *
     * @Di\InjectParams({
     *     "logOperationInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.log_operation_internal_worker")
     * })
     */
    function __construct(
        LogOperationInternalWorker $logOperationInternalWorker)
    {
        $this->logOperationInternalWorker = $logOperationInternalWorker;
    }

    /**
     * Logs a trial operation.
     *
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string[] $topics
     * @param int      $timestamp
     */
    public function logTrial($mobile, $uniqueness, $topics, $timestamp)
    {
        $this->logOperationInternalWorker->logTrial(
            $mobile,
            $uniqueness,
            $topics,
            $timestamp
        );
    }

    /**
     * Logs a create operation.
     *
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string[] $topics
     * @param int      $amount
     * @param int      $timestamp
     */
    public function logCreate($mobile, $uniqueness, $topics, $amount, $timestamp)
    {
        $this->logOperationInternalWorker->logCreate(
            $mobile,
            $uniqueness,
            $topics,
            $amount,
            $timestamp
        );
    }
}
