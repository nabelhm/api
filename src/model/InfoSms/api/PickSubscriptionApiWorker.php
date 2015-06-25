<?php

namespace Muchacuba\InfoSms;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Subscription\NonExistentMobileAndUniquenessApiException;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickSubscriptionApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Picks the subscription with given mobile and uniqueness.
     *
     * @param string $mobile
     * @param string $uniqueness
     *
     * @return array A subscription as an array with the following keys:
     *               mobile, uniqueness, alias, topics, balance and active
     *
     * @throws NonExistentMobileAndUniquenessApiException
     */
    public function pick($mobile, $uniqueness)
    {
        $subscription = $this->connectToStorageInternalWorker->connect()
            ->findOne(
                [
                    'mobile' => $mobile,
                    'uniqueness' => $uniqueness
                ],
                [
                    '_id' => 0, // Exclude
                    'order' => 0 // Exclude
                ]
            );

        if (!$subscription) {
            throw new NonExistentMobileAndUniquenessApiException();
        }

        return $subscription;
    }
}
