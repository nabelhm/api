<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class IncreaseBalanceInternalWorker
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
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    ) {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Increase one sms to the subscription with given mobile
     *
     * @param string $mobile
     *
     * @throws NonExistentMobileInternalException
     */
    public function increase($mobile)
    {
        $result = $this->connectToStorageInternalWorker->connect()->update(
            array('mobile' => $mobile),
            array('$inc' => array(
                'balance' => 1
            ))
        );

        if ($result['n'] == 0) {
            throw new NonExistentMobileInternalException($mobile);
        }
    }
}
