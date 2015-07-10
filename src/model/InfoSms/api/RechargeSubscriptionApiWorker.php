<?php

namespace Muchacuba\InfoSms;

use Cubalider\Sms\EnqueueMessageApiWorker;
use Muchacuba\InfoSms\Profile\CheckBalanceInternalWorker;
use Muchacuba\InfoSms\Profile\DecreaseBalanceInternalWorker;
use Muchacuba\InfoSms\ResellPackage\NonExistentIdInternalException as NonExistentResellPackageInternalException;
use Muchacuba\InfoSms\Subscription\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Profile\InsufficientBalanceApiException;
use Muchacuba\InfoSms\Subscription\LogOperationInternalWorker;
use Muchacuba\InfoSms\Subscription\NonExistentMobileAndUniquenessApiException;
use Muchacuba\InfoSms\Subscription\NonExistentTopicApiException;
use Muchacuba\InfoSms\Subscription\NoResellPackageApiException;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\NoTopicsApiException;
use Muchacuba\InfoSms\Subscription\TrialNotAcceptedApiException;
use Muchacuba\InfoSms\Topic\NonExistentIdApiException;
use Muchacuba\InfoSms\Subscription\LowBalanceReminder\DeleteLogInternalWorker as DeleteLowBalanceReminderLogInternalWorker;
use Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log\NonExistentMobileInternalException;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class RechargeSubscriptionApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var PickTopicApiWorker
     */
    private $pickTopicApiWorker;

    /**
     * @var PickResellPackageInternalWorker
     */
    private $pickResellPackageInternalWorker;

    /**
     * @var CheckBalanceInternalWorker
     */
    private $checkBalanceInternalWorker;

    /**
     * @var DecreaseBalanceInternalWorker
     */
    private $decreaseBalanceInternalWorker;

    /**
     * @var LogOperationInternalWorker
     */
    private $logOperationInternalWorker;

    /**
     * @var EnqueueMessageApiWorker
     */
    private $enqueueMessageApiWorker;

    /**
     * @var DeleteLowBalanceReminderLogInternalWorker
     */
    private $deleteLowBalanceReminderLogInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker            $connectToStorageInternalWorker
     * @param PickTopicApiWorker                        $pickTopicApiWorker
     * @param PickResellPackageInternalWorker           $pickResellPackageInternalWorker
     * @param CheckBalanceInternalWorker                $checkBalanceInternalWorker
     * @param DecreaseBalanceInternalWorker             $decreaseBalanceInternalWorker
     * @param LogOperationInternalWorker                $logOperationInternalWorker
     * @param EnqueueMessageApiWorker                   $enqueueMessageApiWorker
     * @param DeleteLowBalanceReminderLogInternalWorker $deleteLowBalanceReminderLogInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker"            = @Di\Inject("muchacuba.info_sms.subscription.connect_to_storage_internal_worker"),
     *     "pickTopicApiWorker"                        = @Di\Inject("muchacuba.info_sms.pick_topic_api_worker"),
     *     "pickResellPackageInternalWorker"           = @Di\Inject("muchacuba.info_sms.pick_resell_package_internal_worker"),
     *     "checkBalanceInternalWorker"                = @Di\Inject("muchacuba.info_sms.profile.check_balance_internal_worker"),
     *     "decreaseBalanceInternalWorker"             = @Di\Inject("muchacuba.info_sms.profile.decrease_balance_internal_worker"),
     *     "logOperationInternalWorker"                = @Di\Inject("muchacuba.info_sms.subscription.log_operation_internal_worker"),
     *     "enqueueMessageApiWorker"                   = @Di\Inject("cubalider.sms.enqueue_message_api_worker"),
     *     "deleteLowBalanceReminderLogInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.low_balance_reminder.delete_log_internal_worker")
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        PickTopicApiWorker $pickTopicApiWorker,
        PickResellPackageInternalWorker $pickResellPackageInternalWorker,
        CheckBalanceInternalWorker $checkBalanceInternalWorker,
        DecreaseBalanceInternalWorker $decreaseBalanceInternalWorker,
        LogOperationInternalWorker $logOperationInternalWorker,
        EnqueueMessageApiWorker $enqueueMessageApiWorker,
        DeleteLowBalanceReminderLogInternalWorker $deleteLowBalanceReminderLogInternalWorker
    ) {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->pickTopicApiWorker = $pickTopicApiWorker;
        $this->pickResellPackageInternalWorker = $pickResellPackageInternalWorker;
        $this->checkBalanceInternalWorker = $checkBalanceInternalWorker;
        $this->decreaseBalanceInternalWorker = $decreaseBalanceInternalWorker;
        $this->logOperationInternalWorker = $logOperationInternalWorker;
        $this->enqueueMessageApiWorker = $enqueueMessageApiWorker;
        $this->deleteLowBalanceReminderLogInternalWorker = $deleteLowBalanceReminderLogInternalWorker;
    }

    /**
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string[] $topics
     * @param string   $resellPackage
     *
     * @throws NonExistentMobileAndUniquenessApiException
     * @throws NoTopicsApiException
     * @throws NonExistentTopicApiException
     * @throws NoResellPackageApiException
     * @throws NonExistentResellPackageInternalException
     * @throws TrialNotAcceptedApiException
     * @throws InsufficientBalanceApiException
     */
    public function recharge($mobile, $uniqueness, $topics, $resellPackage)
    {
        $subscription = $this->connectToStorageInternalWorker->connect()
            ->findOne([
                'mobile' => $mobile,
                'uniqueness' => $uniqueness,
            ]);

        if (!$subscription) {
            throw new NonExistentMobileAndUniquenessApiException();
        }

        if (count($topics) == 0) {
            throw new NoTopicsApiException();
        }

        foreach ($topics as $topic) {
            try {
                $this->pickTopicApiWorker->pick($topic);
            } catch (NonExistentIdApiException $e) {
                throw new NonExistentTopicApiException();
            }
        }

        if (!$resellPackage) {
            throw new NoResellPackageApiException();
        }

        // Pick resell package to use amount

        try {
            $resellPackage = $this->pickResellPackageInternalWorker->pick(
                $resellPackage
            );
        } catch (NonExistentResellPackageInternalException $e) {
            throw $e;
        }

        if ($resellPackage['price'] == 0) {
            throw new TrialNotAcceptedApiException();
        }

        if (!$this->checkBalanceInternalWorker->check(
            $uniqueness,
            $resellPackage['amount']
        )) {
            throw new InsufficientBalanceApiException();
        }

        $this->connectToStorageInternalWorker->connect()->update(
            [
                'mobile' => $mobile,
                'uniqueness' => $uniqueness
            ],
            [
                '$set' => [
                    'topics' => $topics,
                ],
                '$inc' => [
                    'balance' => $resellPackage['amount']
                ]
            ]
        );

        $this->decreaseBalanceInternalWorker->decrease(
            $uniqueness,
            $resellPackage['amount']
        );

        $this->enqueueMessageApiWorker->enqueue(
            $mobile,
            sprintf(
                "Tu telefono se ha recargado con %s sms para seguir recibiendo noticias.",
                $resellPackage['amount']
            )
        );

        $this->logOperationInternalWorker->logRecharge(
            $mobile,
            $uniqueness,
            $topics,
            $subscription['trial'],
            $subscription['balance'],
            $resellPackage['amount'],
            time()
        );

        try {
            $this->deleteLowBalanceReminderLogInternalWorker->delete($mobile);
        } catch (NonExistentMobileInternalException $e) {
            // This subscription did not have a low balance reminder log
        }
    }
}
