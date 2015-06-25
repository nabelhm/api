<?php

namespace Muchacuba\InfoSms;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * 
 * @Di\Service()
 */
class CreateSubscriptionTestWorker
{
    /**
     * @var CreateSubscriptionInternalWorker
     */
    private $createSubscriptionInternalWorker;

    /**
     * @param CreateSubscriptionInternalWorker $createSubscriptionInternalWorker
     *
     * @Di\InjectParams({
     *     "createSubscriptionInternalWorker" = @Di\Inject("muchacuba.info_sms.create_subscription_internal_worker")
     * })
     */
    public function __construct(
        CreateSubscriptionInternalWorker $createSubscriptionInternalWorker
    ) {
        $this->createSubscriptionInternalWorker = $createSubscriptionInternalWorker;
    }

    /**
     * Creates a subscription.
     *
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string   $alias
     * @param string[] $topics
     * @param int      $trial
     * @param int      $balance
     * @param boolean  $active
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
        $this->createSubscriptionInternalWorker->create(
            $mobile,
            $uniqueness,
            $alias,
            $topics,
            $trial,
            $balance,
            $active
        );
    }
}
