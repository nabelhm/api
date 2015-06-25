<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DecreaseBalanceInternalWorker
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
     * Decreases one sms to the subscription with given mobile.
     *
     * @param string $mobile
     *
     * @throws NonExistentMobileInternalException
     * @throws InactiveInternalException
     * @throws InsufficientBalanceInternalException
     */
    public function decrease($mobile)
    {
        $item = $this->connectToStorageInternalWorker->connect()->findOne([
            'mobile' => $mobile
        ]);

        if (!$item) {
            throw new NonExistentMobileInternalException($mobile);
        }

        if ($item['active'] == false) {
            throw new InactiveInternalException();
        }

        if ($item['trial'] > 0) {
            $this->connectToStorageInternalWorker->connect()->update(
                array('mobile' => $mobile),
                array('$inc' => array(
                    'trial' => -1
                ))
            );
        } elseif ($item['balance'] > 0) {
            $this->connectToStorageInternalWorker->connect()->update(
                array('mobile' => $mobile),
                array('$inc' => array(
                    'balance' => -1
                ))
            );
        } else {
            throw new InsufficientBalanceInternalException();
        }
    }
}
