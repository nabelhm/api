<?php

namespace Cubalider\Sms;

use Cubalider\Sms\Message\EmptyQueueInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class SendAndDeliverMessageTestWorker
{
    /**
     * @var PopMessageInternalWorker
     */
    private $popMessageInternalWorker;

    /**
     * @var LogDeliveryOperationApiWorker
     */
    private $logDeliveryOperationApiWorker;

    /**
     * @param PopMessageInternalWorker       $popMessageInternalWorker
     * @param LogDeliveryOperationApiWorker  $logDeliveryOperationApiWorker
     *
     * @Di\InjectParams({
     *     "popMessageInternalWorker"      = @Di\Inject("cubalider.sms.pop_message_internal_worker"),
     *     "logDeliveryOperationApiWorker" = @DI\Inject("cubalider.sms.log_delivery_operation_api_worker"),
     * })
     */
    public function __construct(
        PopMessageInternalWorker $popMessageInternalWorker,
        LogDeliveryOperationApiWorker $logDeliveryOperationApiWorker
    )
    {
        $this->popMessageInternalWorker = $popMessageInternalWorker;
        $this->logDeliveryOperationApiWorker = $logDeliveryOperationApiWorker;
    }

    /**
     * Sends and delivers a message.
     *
     * @param boolean|null $success
     */
    public function sendAndDeliver($success = true)
    {
        try {
            $message = $this->popMessageInternalWorker->pop();
        } catch (EmptyQueueInternalException $e) {
            return;
        }

        $this->logDeliveryOperationApiWorker->log(
            $message['message'],
            $success ? 'Delivered' : 'Not Delivered',
            time()
        );
    }
}
