<?php

namespace Muchacuba\RechargeCard\Profile\Debt;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
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
     *     "logOperationInternalWorker" = @Di\Inject("muchacuba.recharge_card.profile.debt.log_operation_internal_worker")
     * })
     */
    public function __construct(LogOperationInternalWorker $logOperationInternalWorker)
    {
        $this->logOperationInternalWorker = $logOperationInternalWorker;
    }

    /**
     * Logs an operation.
     *
     * @param string $uniqueness
     * @param int    $amount
     * @param string $impact
     * @param string $description
     */
    public function log($uniqueness, $amount, $impact, $description)
    {
        $this->logOperationInternalWorker->log(
            $uniqueness,
            $amount,
            $impact,
            $description
        );
    }
}
