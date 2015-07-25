<?php

namespace Muchacuba\InfoSms\Subscription;

use Cubalider\Sms\DeliveryEvent;
use Muchacuba\InfoSms\Message\DeleteLinkInternalWorker;
use Muchacuba\InfoSms\Message\Link\NonExistentMessageInternalException;
use Muchacuba\InfoSms\Message\PickLinkInternalWorker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Muchacuba\InfoSms\Subscription\IncreaseBalanceInternalWorker as IncreaseSubscriptionBalanceWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class IncreaseBalanceDeliveryOperationListener implements EventSubscriberInterface
{
    /**
     * @var PickLinkInternalWorker
     */
    private $pickLinkInternalWorker;

    /**
     * @var DeleteLinkInternalWorker
     */
    private $deleteLinkInternalWorker;

    /**
     * @var IncreaseSubscriptionBalanceWorker
     */
    private $increaseSubscriptionBalanceWorker;

    /**
     * @param PickLinkInternalWorker            $pickLinkInternalWorker
     * @param DeleteLinkInternalWorker          $deleteLinkInternalWorker
     * @param IncreaseSubscriptionBalanceWorker $increaseSubscriptionBalanceWorker
     *
     * @Di\InjectParams({
     *     "pickLinkInternalWorker"            = @Di\Inject("muchacuba.info_sms.message.pick_link_internal_worker"),
     *     "deleteLinkInternalWorker"          = @Di\Inject("muchacuba.info_sms.message.delete_link_internal_worker"),
     *     "increaseSubscriptionBalanceWorker" = @Di\Inject("muchacuba.info_sms.subscription.increase_balance_internal_worker")
     * })
     */
    function __construct(
        PickLinkInternalWorker $pickLinkInternalWorker,
        DeleteLinkInternalWorker $deleteLinkInternalWorker,
        IncreaseSubscriptionBalanceWorker $increaseSubscriptionBalanceWorker
    )
    {
        $this->pickLinkInternalWorker = $pickLinkInternalWorker;
        $this->deleteLinkInternalWorker = $deleteLinkInternalWorker;
        $this->increaseSubscriptionBalanceWorker = $increaseSubscriptionBalanceWorker;
    }

    /**
     * @param DeliveryEvent $event
     *
     * @Di\Observe("cubalider.sms.not_delivered")
     */
    public function increase(DeliveryEvent $event)
    {
        try {
            $link = $this->pickLinkInternalWorker->pick($event->getMessage());

            try {
                $this->increaseSubscriptionBalanceWorker->increase($link['subscription']);
            } catch (NonExistentMobileInternalException $e) {
                // Maybe subscription was deleted, then ignore it
            }

            // TODO: Delete link in a post event
            $this->deleteLinkInternalWorker->delete($event->getMessage());
        } catch (NonExistentMessageInternalException $e) {
        }
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'cubalider.sms.not_delivered' => 'increase'
        ];
    }

}
