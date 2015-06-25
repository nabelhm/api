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
    public function __construct(
        LogOperationInternalWorker $logOperationInternalWorker
    ) {
        $this->logOperationInternalWorker = $logOperationInternalWorker;
    }

    /**
     * Creates a trial log.
     *
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string[] $topics
     */
    public function logTrial($mobile, $uniqueness, $topics)
    {
        $this->logOperationInternalWorker->logTrial(
            $mobile,
            $uniqueness,
            $topics
        );
    }

    /**
     * Creates a create log.
     *
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string[] $topics
     * @param int      $amount
     */
    public function logCreate($mobile, $uniqueness, $topics, $amount)
    {
        $this->logOperationInternalWorker->logCreate(
            $mobile,
            $uniqueness,
            $topics,
            $amount
        );
    }
}
