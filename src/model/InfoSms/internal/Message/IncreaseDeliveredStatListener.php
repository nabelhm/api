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
class IncreaseDeliveredStatListener
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
     * @var IncreaseDeliveredStatInternalWorker
     */
    private $increaseDeliveredStatInternalWorker;

    /**
     * @param PickLinkInternalWorker              $pickLinkInternalWorker
     * @param DeleteLinkInternalWorker            $deleteLinkInternalWorker
     * @param IncreaseDeliveredStatInternalWorker $increaseDeliveredStatInternalWorker
     *
     * @Di\InjectParams({
     *     "pickLinkInternalWorker"              = @Di\Inject("muchacuba.info_sms.message.pick_link_internal_worker"),
     *     "deleteLinkInternalWorker"            = @Di\Inject("muchacuba.info_sms.message.delete_link_internal_worker"),
     *     "increaseDeliveredStatInternalWorker" = @Di\Inject("muchacuba.info_sms.message.increase_delivered_stat_internal_worker")
     * })
     */
    function __construct(
        PickLinkInternalWorker $pickLinkInternalWorker,
        DeleteLinkInternalWorker $deleteLinkInternalWorker,
        IncreaseDeliveredStatInternalWorker $increaseDeliveredStatInternalWorker
    )
    {
        $this->pickLinkInternalWorker = $pickLinkInternalWorker;
        $this->deleteLinkInternalWorker = $deleteLinkInternalWorker;
        $this->increaseDeliveredStatInternalWorker = $increaseDeliveredStatInternalWorker;
    }

    /**
     * @param DeliveryEvent $event
     *
     * @Di\Observe("cubalider.sms.delivered")
     */
    public function increase(DeliveryEvent $event)
    {
        try {
            $link = $this->pickLinkInternalWorker->pick($event->getMessage());

            $this->increaseDeliveredStatInternalWorker->increase($link['info']);

            $this->deleteLinkInternalWorker->delete($event->getMessage());
        } catch (NonExistentMessageInternalException $e) {
        }
    }
}
