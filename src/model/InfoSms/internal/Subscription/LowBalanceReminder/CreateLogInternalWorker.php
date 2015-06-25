<?php

namespace Muchacuba\InfoSms\Subscription\LowBalanceReminder;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log\ExistentMobileInternalException;
use Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log\ConnectToStorageInternalWorker;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateLogInternalWorker
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
     * Creates a log for given mobile.
     *
     * @param string $mobile
     *
     * @throws ExistentMobileInternalException
     * @throws \MongoCursorException
     */
    public function create($mobile)
    {
        try {
            $this->connectToStorageInternalWorker->connect()->insert(array(
                'mobile' => $mobile
            ));
        } catch (\MongoCursorException $e) {
            if (11000 == $e->getCode()) {
                throw new ExistentMobileInternalException();
            }

            throw $e;
        }
    }
}
