<?php

namespace Muchacuba\InfoSms;

use Cubalider\Sms\EnqueueMessageApiWorker;
use Muchacuba\InfoSms\Info\EmptyQueueInternalException;
use Muchacuba\InfoSms\Message\CreateLinkInternalWorker;
use Muchacuba\InfoSms\Message\CreateStatInternalWorker;
use Muchacuba\InfoSms\Subscription\InactiveInternalException;
use Muchacuba\InfoSms\Subscription\InsufficientBalanceInternalException;
use Muchacuba\InfoSms\Subscription\DecreaseBalanceInternalWorker as DecreaseSubscriptionBalanceInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ProcessInfosApiWorker
{
    /**
     * @var PopInfoInternalWorker
     */
    private $popInfoInternalWorker;

    /**
     * @var CollectSubscriptionsByTopicsInternalWorker
     */
    private $collectSubscriptionsByTopicsInternalWorker;

    /**
     * @var DecreaseSubscriptionBalanceInternalWorker
     */
    private $decreaseSubscriptionBalanceInternalWorker;

    /**
     * @var EnqueueMessageApiWorker
     */
    private $enqueueMessageApiWorker;

    /**
     * @var CreateLinkInternalWorker
     */
    private $createLinkInternalWorker;

    /**
     * @var CreateStatInternalWorker
     */
    private $createStatInternalWorker;

    /**
     * @param PopInfoInternalWorker                      $popInfoInternalWorker
     * @param CollectSubscriptionsByTopicsInternalWorker $collectSubscriptionsByTopicsInternalWorker
     * @param DecreaseSubscriptionBalanceInternalWorker  $decreaseSubscriptionBalanceInternalWorker
     * @param EnqueueMessageApiWorker                    $enqueueMessageApiWorker
     * @param CreateLinkInternalWorker                   $createLinkInternalWorker
     * @param CreateStatInternalWorker                   $createStatInternalWorker
     *
     * @Di\InjectParams({
     *     "popInfoInternalWorker"                      = @Di\Inject("muchacuba.info_sms.pop_info_internal_worker"),
     *     "collectSubscriptionsByTopicsInternalWorker" = @Di\Inject("muchacuba.info_sms.collect_subscriptions_by_topics_internal_worker"),
     *     "decreaseSubscriptionBalanceInternalWorker"  = @Di\Inject("muchacuba.info_sms.subscription.decrease_balance_internal_worker"),
     *     "enqueueMessageApiWorker"                    = @Di\Inject("cubalider.sms.enqueue_message_api_worker"),
     *     "createLinkInternalWorker"                   = @Di\Inject("muchacuba.info_sms.message.create_link_internal_worker"),
     *     "createStatInternalWorker"                   = @Di\Inject("muchacuba.info_sms.message.create_stat_internal_worker")
     * })
     */
    function __construct(
        PopInfoInternalWorker $popInfoInternalWorker,
        CollectSubscriptionsByTopicsInternalWorker $collectSubscriptionsByTopicsInternalWorker,
        DecreaseSubscriptionBalanceInternalWorker $decreaseSubscriptionBalanceInternalWorker,
        EnqueueMessageApiWorker $enqueueMessageApiWorker,
        CreateLinkInternalWorker $createLinkInternalWorker,
        CreateStatInternalWorker $createStatInternalWorker
    )
    {
        $this->popInfoInternalWorker = $popInfoInternalWorker;
        $this->collectSubscriptionsByTopicsInternalWorker = $collectSubscriptionsByTopicsInternalWorker;
        $this->decreaseSubscriptionBalanceInternalWorker = $decreaseSubscriptionBalanceInternalWorker;
        $this->enqueueMessageApiWorker = $enqueueMessageApiWorker;
        $this->createLinkInternalWorker = $createLinkInternalWorker;
        $this->createStatInternalWorker = $createStatInternalWorker;
    }

    /**
     * Processes infos.
     */
    public function process()
    {
        while (true) {
            try {
                // Pops oldest info from list
                $info = $this->popInfoInternalWorker->pop();

                // Finds subscriptions with given topics
                $subscriptions = $this->collectSubscriptionsByTopicsInternalWorker->collect(
                    $info['topics']
                );

                $total = 0;
                // Iterate over subscriptions
                foreach ($subscriptions as $subscription) {
                    try {
                        $this->decreaseSubscriptionBalanceInternalWorker->decrease(
                            $subscription['mobile']
                        );
                    } catch (InactiveInternalException $e) {
                        // Ignore the subscription and continue with the next one
                        continue;
                    } catch (InsufficientBalanceInternalException $e) {
                        // Ignore the subscription and continue with the next one
                        continue;
                    }

                    // Create message
                    $message = $this->enqueueMessageApiWorker->enqueue(
                        $subscription['mobile'],
                        $info['body']
                    );

                    $this->createLinkInternalWorker->create(
                        $message,
                        $info['id'],
                        $subscription['mobile']
                    );

                    $total++;
                }

                $this->createStatInternalWorker->create(
                    $info,
                    $total,
                    time()
                );
            } catch (EmptyQueueInternalException $e) {
                return;
            }
        }
    }
}
