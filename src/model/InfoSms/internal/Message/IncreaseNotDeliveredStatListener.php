<?php

namespace Muchacuba\InfoSms\Message;

use Cubalider\Sms\DeliveryEvent;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Message\Link\NonExistentMessageInternalException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class IncreaseNotDeliveredStatListener
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
     * @var IncreaseNotDeliveredStatInternalWorker
     */
    private $increaseNotDeliveredStatInternalWorker;

    /**
     * @param PickLinkInternalWorker                 $pickLinkInternalWorker
     * @param DeleteLinkInternalWorker               $deleteLinkInternalWorker
     * @param IncreaseNotDeliveredStatInternalWorker $increaseNotDeliveredStatInternalWorker
     *
     * @Di\InjectParams({
     *     "pickLinkInternalWorker"                 = @Di\Inject("muchacuba.info_sms.message.pick_link_internal_worker"),
     *     "deleteLinkInternalWorker"               = @Di\Inject("muchacuba.info_sms.message.delete_link_internal_worker"),
     *     "increaseNotDeliveredStatInternalWorker" = @Di\Inject("muchacuba.info_sms.message.increase_not_delivered_stat_internal_worker")
     * })
     */
    function __construct(
        PickLinkInternalWorker $pickLinkInternalWorker,
        DeleteLinkInternalWorker $deleteLinkInternalWorker,
        IncreaseNotDeliveredStatInternalWorker $increaseNotDeliveredStatInternalWorker
    )
    {
        $this->pickLinkInternalWorker = $pickLinkInternalWorker;
        $this->deleteLinkInternalWorker = $deleteLinkInternalWorker;
        $this->increaseNotDeliveredStatInternalWorker = $increaseNotDeliveredStatInternalWorker;
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

            $this->increaseNotDeliveredStatInternalWorker->increase($link['info']);

            $this->deleteLinkInternalWorker->delete($event->getMessage());
        } catch (NonExistentMessageInternalException $e) {
        }
    }
}
