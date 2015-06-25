<?php

namespace Muchacuba\InfoSms\Subscription\LowBalanceReminder;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateLogTestWorker
{
    /**
     * @var CreateLogInternalWorker
     */
    private $createLogInternalWorker;

    /**
     * @param CreateLogInternalWorker $createLogInternalWorker
     *
     * @Di\InjectParams({
     *     "createLogInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.low_balance_reminder.create_log_internal_worker")
     * })
     */
    public function __construct(CreateLogInternalWorker $createLogInternalWorker)
    {
        $this->createLogInternalWorker = $createLogInternalWorker;
    }

    /**
     * @param string $mobile
     */
    public function create($mobile)
    {
        $this->createLogInternalWorker->create($mobile);
    }
}
