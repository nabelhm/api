<?php

namespace Muchacuba\InfoSms\Subscription;

use Cubalider\Sms\EnqueueMessageApiWorker;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\LowBalanceReminder\CreateLogInternalWorker;
use Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log\ExistentMobileInternalException as ExistentLowBalanceReminderLogMobileInternalException;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class SendLowBalanceReminderInternalWorker
{
    /**
     * @var CreateLogInternalWorker
     */
    private $createLogInternalWorker;

    /**
     * @var EnqueueMessageApiWorker
     */
    private $enqueueMessageApiWorker;

    /**
     * @param CreateLogInternalWorker $createLogInternalWorker
     * @param EnqueueMessageApiWorker $enqueueMessageApiWorker
     *
     * @Di\InjectParams({
     *     "createLogInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.low_balance_reminder.create_log_internal_worker"),
     *     "enqueueMessageApiWorker" = @Di\Inject("cubalider.sms.enqueue_message_api_worker")
     * })
     */
    public function __construct(
        CreateLogInternalWorker $createLogInternalWorker,
        EnqueueMessageApiWorker $enqueueMessageApiWorker
    ) {
        $this->createLogInternalWorker = $createLogInternalWorker;
        $this->enqueueMessageApiWorker = $enqueueMessageApiWorker;
    }

    /**
     * @param string $mobile
     * @param int    $balance
     */
    public function send($mobile, $balance)
    {
        try {
            $this->createLogInternalWorker->create($mobile);
        } catch (ExistentLowBalanceReminderLogMobileInternalException $e) {
            return;
        }

        $this->enqueueMessageApiWorker->enqueue(
            $mobile,
            sprintf(
                "El saldo de tu subscripcion es de %s sms. Recarga con la persona que hiciste la subscripcion para seguir recibiendo noticias.",
                $balance
            )
        );
    }
}
