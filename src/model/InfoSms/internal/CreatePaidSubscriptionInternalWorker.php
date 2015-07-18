<?php

namespace Muchacuba\InfoSms;

use Cubalider\Sms\EnqueueMessageApiWorker;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Profile\CheckBalanceInternalWorker;
use Muchacuba\InfoSms\Profile\DecreaseBalanceInternalWorker;
use Muchacuba\InfoSms\Subscription\CheckOperationInternalWorker;
use Muchacuba\InfoSms\Subscription\ExistentMobileInternalException;
use Muchacuba\InfoSms\Subscription\InsufficientBalanceInternalException;
use Muchacuba\InfoSms\Subscription\LogOperationInternalWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * 
 * @Di\Service()
 */
class CreatePaidSubscriptionInternalWorker
{
    /**
     * @var CheckBalanceInternalWorker
     */
    private $checkBalanceInternalWorker;

    /**
     * @var CreateSubscriptionInternalWorker
     */
    private $createSubscriptionInternalWorker;

    /**
     * @var DecreaseBalanceInternalWorker
     */
    private $decreaseBalanceInternalWorker;

    /**
     * @var CheckOperationInternalWorker
     */
    private $checkOperationInternalWorker;

    /**
     * @var EnqueueMessageApiWorker
     */
    private $enqueueMessageApiWorker;

    /**
     * @var LogOperationInternalWorker
     */
    private $logOperationInternalWorker;

    /**
     * @param CheckBalanceInternalWorker       $checkBalanceInternalWorker
     * @param CreateSubscriptionInternalWorker $createSubscriptionInternalWorker
     * @param DecreaseBalanceInternalWorker    $decreaseBalanceInternalWorker
     * @param CheckOperationInternalWorker     $checkOperationInternalWorker
     * @param EnqueueMessageApiWorker          $enqueueMessageApiWorker
     * @param LogOperationInternalWorker       $logOperationInternalWorker
     *
     * @Di\InjectParams({
     *     "checkBalanceInternalWorker"       = @Di\Inject("muchacuba.info_sms.profile.check_balance_internal_worker"),
     *     "createSubscriptionInternalWorker" = @Di\Inject("muchacuba.info_sms.create_subscription_internal_worker"),
     *     "decreaseBalanceInternalWorker"    = @Di\Inject("muchacuba.info_sms.profile.decrease_balance_internal_worker"),
     *     "checkOperationInternalWorker"     = @Di\Inject("muchacuba.info_sms.subscription.check_operation_internal_worker"),
     *     "enqueueMessageApiWorker"          = @Di\Inject("cubalider.sms.enqueue_message_api_worker"),
     *     "logOperationInternalWorker"       = @Di\Inject("muchacuba.info_sms.subscription.log_operation_internal_worker")
     * })
     */
    public function __construct(
        CheckBalanceInternalWorker $checkBalanceInternalWorker,
        CreateSubscriptionInternalWorker $createSubscriptionInternalWorker,
        DecreaseBalanceInternalWorker $decreaseBalanceInternalWorker,
        CheckOperationInternalWorker $checkOperationInternalWorker,
        EnqueueMessageApiWorker $enqueueMessageApiWorker,
        LogOperationInternalWorker $logOperationInternalWorker
    ) {
        $this->checkBalanceInternalWorker = $checkBalanceInternalWorker;
        $this->createSubscriptionInternalWorker = $createSubscriptionInternalWorker;
        $this->decreaseBalanceInternalWorker = $decreaseBalanceInternalWorker;
        $this->checkOperationInternalWorker = $checkOperationInternalWorker;
        $this->enqueueMessageApiWorker = $enqueueMessageApiWorker;
        $this->logOperationInternalWorker = $logOperationInternalWorker;
    }

    /**
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string   $alias
     * @param string[] $topics
     * @param array    $resellPackage
     *
     * @throws InsufficientBalanceInternalException
     * @throws ExistentMobileInternalException
     */
    public function create(
        $mobile,
        $uniqueness,
        $alias,
        $topics,
        $resellPackage
    )
    {
        if (!$this->checkBalanceInternalWorker->check(
            $uniqueness,
            $resellPackage['amount']
        )) {
            throw new InsufficientBalanceInternalException();
        }

        try {
            $this->createSubscriptionInternalWorker->create(
                $mobile,
                $uniqueness,
                $alias,
                $topics,
                0,
                $resellPackage['amount'],
                true
            );
        } catch (ExistentMobileInternalException $e) {
            throw $e;
        }

        $this->decreaseBalanceInternalWorker->decrease(
            $uniqueness,
            $resellPackage['amount']
        );

        if (!$this->checkOperationInternalWorker->checkTrial($mobile)
            && !$this->checkOperationInternalWorker->checkCreate($mobile)
        ) {
            $this->enqueueMessageApiWorker->enqueue(
                $mobile,
                sprintf(
                    "Tu teléfono se ha suscrito con %s sms para recibir noticias %s que seleccionaste.",
                    $resellPackage['amount'],
                    count($topics) == 1 ? "del tópico" : sprintf("de los %s tópicos", count($topics))
                )
            );
        }

        $this->logOperationInternalWorker->logCreate(
            $mobile,
            $uniqueness,
            $topics,
            $resellPackage['amount'],
            time()
        );
    }
}
