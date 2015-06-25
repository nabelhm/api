<?php

namespace Cubalider\Sms;

use Cubalider\Sms\Message\EmptyQueueInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class SendMessagesApiWorker
{
    /**
     * @var PopMessageInternalWorker
     */
    private $popMessageInternalWorker;

    /**
     * @var SendMessageInternalWorker
     */
    private $sendMessageInternalWorker;

    /**
     * @param PopMessageInternalWorker  $popMessageInternalWorker
     * @param SendMessageInternalWorker $sendMessageInternalWorker
     *
     * @Di\InjectParams({
     *     "popMessageInternalWorker"  = @Di\Inject("cubalider.sms.pop_message_internal_worker"),
     *     "sendMessageInternalWorker" = @Di\Inject("cubalider.sms.send_message_internal_worker")
     * })
     */
    function __construct(
        PopMessageInternalWorker $popMessageInternalWorker,
        SendMessageInternalWorker $sendMessageInternalWorker
    )
    {
        $this->popMessageInternalWorker = $popMessageInternalWorker;
        $this->sendMessageInternalWorker = $sendMessageInternalWorker;
    }

    /**
     * Sends given amount of messages.
     *
     * @param int $amount
     */
    public function send($amount = 100)
    {
        for ($i = 0; $i <= $amount; $i++) {
            try {
                $message = $this->popMessageInternalWorker->pop();
            } catch (EmptyQueueInternalException $e) {
                return;
            }

            $this->sendMessageInternalWorker->send(
                $message['message'],
                $message['receiver'],
                $message['body']
            );
        }
    }
}
