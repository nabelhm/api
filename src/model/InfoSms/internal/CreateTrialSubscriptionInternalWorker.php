<?php

namespace Muchacuba\InfoSms;

use Cubalider\Sms\EnqueueMessageApiWorker;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\ExistentMobileInternalException;
use Muchacuba\InfoSms\Subscription\LogOperationInternalWorker;
use Muchacuba\InfoSms\Subscription\CheckOperationInternalWorker;
use Muchacuba\InfoSms\Subscription\TrialNotAcceptedInternalException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * 
 * @Di\Service()
 */
class CreateTrialSubscriptionInternalWorker
{
    /**
     * @var CheckOperationInternalWorker
     */
    private $checkOperationInternalWorker;

    /**
     * @var CreateSubscriptionInternalWorker
     */
    private $createSubscriptionInternalWorker;

    /**
     * @var EnqueueMessageApiWorker
     */
    private $enqueueMessageApiWorker;

    /**
     * @var LogOperationInternalWorker
     */
    private $logOperationInternalWorker;

    /**
     * @param CheckOperationInternalWorker     $checkOperationInternalWorker
     * @param CreateSubscriptionInternalWorker $createSubscriptionInternalWorker
     * @param EnqueueMessageApiWorker          $enqueueMessageApiWorker
     * @param LogOperationInternalWorker       $logOperationInternalWorker
     *
     * @Di\InjectParams({
     *     "checkOperationInternalWorker"      = @Di\Inject("muchacuba.info_sms.subscription.check_operation_internal_worker"),
     *     "createSubscriptionInternalWorker"  = @Di\Inject("muchacuba.info_sms.create_subscription_internal_worker"),
     *     "enqueueMessageApiWorker"           = @Di\Inject("cubalider.sms.enqueue_message_api_worker"),
     *     "logOperationInternalWorker"        = @Di\Inject("muchacuba.info_sms.subscription.log_operation_internal_worker")
     * })
     */
    public function __construct(
        CheckOperationInternalWorker $checkOperationInternalWorker,
        CreateSubscriptionInternalWorker $createSubscriptionInternalWorker,
        EnqueueMessageApiWorker $enqueueMessageApiWorker,
        LogOperationInternalWorker $logOperationInternalWorker
    ) {
        $this->checkOperationInternalWorker = $checkOperationInternalWorker;
        $this->createSubscriptionInternalWorker = $createSubscriptionInternalWorker;
        $this->enqueueMessageApiWorker = $enqueueMessageApiWorker;
        $this->logOperationInternalWorker = $logOperationInternalWorker;
    }

    /**
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string   $alias
     * @param string[] $topics
     * @param int      $amount
     *
     * @throws TrialNotAcceptedInternalException
     * @throws ExistentMobileInternalException
     */
    public function create(
        $mobile,
        $uniqueness,
        $alias,
        $topics,
        $amount
    )
    {
        if ($this->checkOperationInternalWorker->checkTrial($mobile)) {
            throw new TrialNotAcceptedInternalException();
        }

        try {
            $this->createSubscriptionInternalWorker->create(
                $mobile,
                $uniqueness,
                $alias,
                $topics,
                $amount,
                0,
                true
            );
        } catch (ExistentMobileInternalException $e) {
            throw $e;
        }

        $this->enqueueMessageApiWorker->enqueue(
            $mobile,
            sprintf(
                "Tu telefono se ha subscrito con %s sms gratis para recibir noticias %s que seleccionaste.",
                10,
                count($topics) == 1 ? "del topico" : sprintf("de los %s topicos", count($topics))
            )
        );

        $this->logOperationInternalWorker->logTrial(
            $mobile,
            $uniqueness,
            $topics
        );
    }
}
