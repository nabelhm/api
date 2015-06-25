<?php

namespace Muchacuba\InfoSms;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Subscription\ExistentMobileInternalException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * 
 * @Di\Service()
 */
class CreateSubscriptionInternalWorker
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
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string   $alias
     * @param string[] $topics
     * @param int      $trial
     * @param int      $balance
     * @param boolean  $active
     *
     * @throws ExistentMobileInternalException
     * @throws \MongoCursorException
     */
    public function create(
        $mobile,
        $uniqueness,
        $alias,
        $topics,
        $trial,
        $balance,
        $active
    )
    {
        try {
            $this->connectToStorageInternalWorker->connect()->insert([
                'mobile' => $mobile,
                'uniqueness' => $uniqueness,
                'alias' => $alias,
                'topics' => $topics,
                'trial' => $trial,
                'balance' => $balance,
                'active' => $active
            ]);
        } catch (\MongoCursorException $e) {
            if (11000 == $e->getCode()) {
                throw new ExistentMobileInternalException();
            }

            throw $e;
        }
    }
}
