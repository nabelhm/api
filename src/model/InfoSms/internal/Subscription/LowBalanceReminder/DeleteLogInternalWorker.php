<?php

namespace Muchacuba\InfoSms\Subscription\LowBalanceReminder;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log\NonExistentMobileInternalException;
use Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log\ConnectToStorageInternalWorker;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DeleteLogInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.low_balance_reminder.log.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Deletes the log for given mobile.
     *
     * @param string $mobile
     *
     * @throws NonExistentMobileInternalException
     */
    public function delete($mobile)
    {
        $result = $this->connectToStorageInternalWorker->connect()->remove(array(
            'mobile' => $mobile,
        ));

        if ($result['n'] == 0) {
            throw new NonExistentMobileInternalException();
        }
    }
}
