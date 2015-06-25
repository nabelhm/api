<?php

namespace Muchacuba\InfoSms;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Profile\NonExistentUniquenessApiException;
use Muchacuba\InfoSms\Subscription\BlankAliasApiException;
use Muchacuba\InfoSms\Subscription\BlankAliasInternalException;
use Muchacuba\InfoSms\Subscription\ExistentMobileApiException;
use Muchacuba\InfoSms\Subscription\ExistentMobileInternalException;
use Muchacuba\InfoSms\Subscription\InsufficientBalanceApiException;
use Muchacuba\InfoSms\Subscription\InsufficientBalanceInternalException;
use Muchacuba\InfoSms\Subscription\InvalidMobileApiException;
use Muchacuba\InfoSms\Subscription\InvalidMobileInternalException;
use Muchacuba\InfoSms\Subscription\NonExistentResellPackageApiException;
use Muchacuba\InfoSms\Subscription\NonExistentResellPackageInternalException;
use Muchacuba\InfoSms\Subscription\NonExistentTopicApiException;
use Muchacuba\InfoSms\Subscription\NonExistentTopicInternalException;
use Muchacuba\InfoSms\Subscription\NoResellPackageApiException;
use Muchacuba\InfoSms\Subscription\NoTopicsApiException;
use Muchacuba\InfoSms\Subscription\NoTopicsInternalException;
use Muchacuba\InfoSms\Subscription\TrialNotAcceptedApiException;
use Muchacuba\InfoSms\Subscription\TrialNotAcceptedInternalException;
use Muchacuba\InfoSms\Subscription\ValidateMobileAndAliasInternalWorker;
use Muchacuba\InfoSms\Subscription\ValidateTopicsInternalWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class CreateSubscriptionApiWorker
{
    /**
     * @var PickProfileApiWorker
     */
    private $pickProfileApiWorker;

    /**
     * @var ValidateMobileAndAliasInternalWorker
     */
    private $validateMobileAndAliasInternalWorker;

    /**
     * @var ValidateTopicsInternalWorker
     */
    private $validateTopicsInternalWorker;

    /**
     * @var PickResellPackageInternalWorker
     */
    private $pickResellPackageInternalWorker;

    /**
     * @var CreateTrialSubscriptionInternalWorker
     */
    private $createTrialSubscriptionInternalWorker;

    /**
     * @var CreatePaidSubscriptionInternalWorker
     */
    private $createPaidSubscriptionInternalWorker;

    /**
     * @param PickProfileApiWorker                  $pickProfileApiWorker
     * @param ValidateMobileAndAliasInternalWorker  $validateMobileAndAliasInternalWorker
     * @param ValidateTopicsInternalWorker          $validateTopicsInternalWorker
     * @param PickResellPackageInternalWorker       $pickResellPackageInternalWorker
     * @param CreateTrialSubscriptionInternalWorker $createTrialSubscriptionInternalWorker
     * @param CreatePaidSubscriptionInternalWorker  $createPaidSubscriptionInternalWorker
     *
     * @Di\InjectParams({
     *     "pickProfileApiWorker"                  = @Di\Inject("muchacuba.info_sms.pick_profile_api_worker"),
     *     "validateMobileAndAliasInternalWorker"  = @Di\Inject("muchacuba.info_sms.subscription.validate_mobile_and_alias_internal_worker"),
     *     "validateTopicsInternalWorker"          = @Di\Inject("muchacuba.info_sms.subscription.validate_topics_internal_worker"),
     *     "pickResellPackageInternalWorker"       = @Di\Inject("muchacuba.info_sms.pick_resell_package_internal_worker"),
     *     "createTrialSubscriptionInternalWorker" = @Di\Inject("muchacuba.info_sms.create_trial_subscription_internal_worker"),
     *     "createPaidSubscriptionInternalWorker"  = @Di\Inject("muchacuba.info_sms.create_paid_subscription_internal_worker"),
     * })
     */
    public function __construct(
        PickProfileApiWorker $pickProfileApiWorker,
        ValidateMobileAndAliasInternalWorker $validateMobileAndAliasInternalWorker,
        ValidateTopicsInternalWorker $validateTopicsInternalWorker,
        PickResellPackageInternalWorker $pickResellPackageInternalWorker,
        CreateTrialSubscriptionInternalWorker $createTrialSubscriptionInternalWorker,
        CreatePaidSubscriptionInternalWorker $createPaidSubscriptionInternalWorker
    ) {
        $this->pickProfileApiWorker = $pickProfileApiWorker;
        $this->validateMobileAndAliasInternalWorker = $validateMobileAndAliasInternalWorker;
        $this->validateTopicsInternalWorker = $validateTopicsInternalWorker;
        $this->pickResellPackageInternalWorker = $pickResellPackageInternalWorker;
        $this->createTrialSubscriptionInternalWorker = $createTrialSubscriptionInternalWorker;
        $this->createPaidSubscriptionInternalWorker = $createPaidSubscriptionInternalWorker;
    }

    /**
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string   $alias
     * @param string[] $topics
     * @param string   $resellPackage
     *
     * @throws NonExistentUniquenessApiException
     * @throws InvalidMobileApiException
     * @throws BlankAliasApiException
     * @throws ExistentMobileApiException
     * @throws NoTopicsApiException
     * @throws NonExistentTopicApiException
     * @throws NoResellPackageApiException
     * @throws NonExistentResellPackageApiException
     * @throws TrialNotAcceptedApiException
     * @throws InsufficientBalanceApiException
     */
    public function create(
        $mobile,
        $uniqueness,
        $alias,
        $topics,
        $resellPackage
    )
    {
        try {
            $this->pickProfileApiWorker->pick($uniqueness);
        } catch (NonExistentUniquenessApiException $e) {
            throw $e;
        }

        try {
            $this->validateMobileAndAliasInternalWorker->validate($mobile, $alias);
        } catch (InvalidMobileInternalException $e) {
            throw new InvalidMobileApiException();
        } catch (BlankAliasInternalException $e) {
            throw new BlankAliasApiException();
        } catch (ExistentMobileInternalException $e) {
            throw new ExistentMobileApiException();
        }

        try {
            $this->validateTopicsInternalWorker->validate($topics);
        } catch (NoTopicsInternalException $e) {
            throw new NoTopicsApiException();
        } catch (NonExistentTopicInternalException $e) {
            throw new NonExistentTopicApiException();
        }

        if (!$resellPackage) {
            throw new NoResellPackageApiException();
        }

        try {
            $resellPackage = $this->pickResellPackageInternalWorker->pick(
                $resellPackage
            );
        } catch (NonExistentResellPackageInternalException $e) {
            throw new NonExistentResellPackageApiException();
        }

        if ($resellPackage['price'] == 0) {
            try {
                $this->createTrialSubscriptionInternalWorker->create(
                    $mobile,
                    $uniqueness,
                    $alias,
                    $topics,
                    10
                );
            } catch (TrialNotAcceptedInternalException $e) {
                throw new TrialNotAcceptedApiException();
            }
        } else {
            try {
                $this->createPaidSubscriptionInternalWorker->create(
                    $mobile,
                    $uniqueness,
                    $alias,
                    $topics,
                    $resellPackage
                );
            } catch (InsufficientBalanceInternalException $e) {
                throw new InsufficientBalanceApiException();
            }
        }
    }
}
