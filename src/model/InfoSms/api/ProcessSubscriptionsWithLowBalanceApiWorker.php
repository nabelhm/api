<?php

namespace Muchacuba\InfoSms;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\SendLowBalanceReminderInternalWorker;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ProcessSubscriptionsWithLowBalanceApiWorker
{
    /**
     * @var CollectSubscriptionsWithLowBalanceInternalWorker
     */
    private $collectSubscriptionsWithLowBalanceInternalWorker;

    /**
     * @var SendLowBalanceReminderInternalWorker
     */
    private $sendLowBalanceReminderInternalWorker;

    /**
     * @param CollectSubscriptionsWithLowBalanceInternalWorker $collectSubscriptionsWithLowBalanceInternalWorker
     * @param SendLowBalanceReminderInternalWorker             $sendLowBalanceReminderInternalWorker
     *
     * @Di\InjectParams({
     *     "collectSubscriptionsWithLowBalanceInternalWorker" = @Di\Inject("muchacuba.info_sms.collect_subscriptions_with_low_balance_internal_worker"),
     *     "sendLowBalanceReminderInternalWorker"             = @Di\Inject("muchacuba.info_sms.subscription.send_low_balance_reminder_internal_worker")
     * })
     */
    public function __construct(
        CollectSubscriptionsWithLowBalanceInternalWorker $collectSubscriptionsWithLowBalanceInternalWorker,
        SendLowBalanceReminderInternalWorker $sendLowBalanceReminderInternalWorker
    )
    {
        $this->collectSubscriptionsWithLowBalanceInternalWorker = $collectSubscriptionsWithLowBalanceInternalWorker;
        $this->sendLowBalanceReminderInternalWorker = $sendLowBalanceReminderInternalWorker;
    }

    /**
     * Processes subscriptions with low balance
     */
    public function process()
    {
        $subscriptions = $this->collectSubscriptionsWithLowBalanceInternalWorker->collect();
        foreach ($subscriptions as $subscription) {
            $this->sendLowBalanceReminderInternalWorker->send(
                $subscription['mobile'],
                $subscription['balance']
            );
        }
    }
}
