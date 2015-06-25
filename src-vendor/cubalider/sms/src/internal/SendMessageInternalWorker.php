<?php

namespace Cubalider\Sms;

use Cubalider\Sms\Silverstreet\SendMessageInternalWorker as SendMessageSilverstreetInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class SendMessageInternalWorker
{
    const OPERATOR_SILVERSTREET = 'silverstreet';

    /**
     * @var string
     */
    private $operator;

    /**
     * @var SendMessageSilverstreetInternalWorker
     */
    private $sendMessageSilverstreetInternalWorker;

    /**
     * @param string                                $operator
     * @param SendMessageSilverstreetInternalWorker $sendMessageSilverstreetInternalWorker
     *
     * @throws \InvalidArgumentException If operator is invalid
     *
     * @Di\InjectParams({
     *     "operator"                              = @Di\Inject("%cubalider.sms.operator%"),
     *     "sendMessageSilverstreetInternalWorker" = @Di\Inject("cubalider.sms.silverstreet.send_message_internal_worker")
     * })
     */
    function __construct(
        $operator,
        SendMessageSilverstreetInternalWorker $sendMessageSilverstreetInternalWorker
    )
    {
        if (!in_array(
            $operator,
            [
                self::OPERATOR_SILVERSTREET
            ]
        )) {
            throw new \InvalidArgumentException();
        }

        $this->operator = $operator;
        $this->sendMessageSilverstreetInternalWorker = $sendMessageSilverstreetInternalWorker;
    }

    /**
     * @param string $id
     * @param string $to
     * @param string $body
     *
     * @throws InvalidRequestInternalException
     * @throws InvalidParameterInternalException
     * @throws InsufficientCreditInternalException
     */
    public function send($id, $to, $body)
    {
        switch ($this->operator) {
            case self::OPERATOR_SILVERSTREET:
                try {
                    $this->sendMessageSilverstreetInternalWorker->send($id, $to, $body);
                } catch (InvalidRequestInternalException $e) {
                    throw $e;
                } catch (InvalidParameterInternalException $e) {
                    throw $e;
                } catch (InsufficientCreditInternalException $e) {
                    throw $e;
                }

                break;
        }
    }
}
