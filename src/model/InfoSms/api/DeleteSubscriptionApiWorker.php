<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Subscription\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Profile\IncreaseBalanceInternalWorker;
use Muchacuba\InfoSms\Subscription\LogOperationInternalWorker;
use Muchacuba\InfoSms\Subscription\NonExistentMobileAndUniquenessApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class DeleteSubscriptionApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var IncreaseBalanceInternalWorker
     */
    private $increaseBalanceInternalWorker;

    /**
     * @var LogOperationInternalWorker
     */
    private $logOperationInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     * @param IncreaseBalanceInternalWorker  $increaseBalanceInternalWorker
     * @param LogOperationInternalWorker     $logOperationInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.connect_to_storage_internal_worker"),
     *     "increaseBalanceInternalWorker"  = @Di\Inject("muchacuba.info_sms.profile.increase_balance_internal_worker"),
     *     "logOperationInternalWorker"     = @Di\Inject("muchacuba.info_sms.subscription.log_operation_internal_worker"),
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        IncreaseBalanceInternalWorker $increaseBalanceInternalWorker,
        LogOperationInternalWorker $logOperationInternalWorker
    ) {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->increaseBalanceInternalWorker = $increaseBalanceInternalWorker;
        $this->logOperationInternalWorker = $logOperationInternalWorker;
    }

    /**
     * Deletes the subscription with given mobile and uniqueness.
     *
     * @param string $mobile
     * @param string $uniqueness
     *
     * @throws NonExistentMobileAndUniquenessApiException
     */
    public function delete($mobile, $uniqueness)
    {
        $subscription = $this->connectToStorageInternalWorker->connect()->findOne([
            'mobile' => $mobile,
            'uniqueness' => $uniqueness
        ]);

        if (!$subscription) {
            throw new NonExistentMobileAndUniquenessApiException();
        }

        // Increase to the balance what the subscription balance has
        $this->increaseBalanceInternalWorker->increase(
            $uniqueness,
            $subscription['balance']
        );

        // Remove subscription
        $this->connectToStorageInternalWorker->connect()->remove([
            'mobile' => $mobile,
            'uniqueness' => $uniqueness
        ]);

        $this->logOperationInternalWorker->logDelete(
            $mobile,
            $uniqueness,
            $subscription['topics'],
            $subscription['trial'],
            $subscription['balance'],
            time()
        );
    }
}
