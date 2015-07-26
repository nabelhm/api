<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Me;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectSubscriptionsApiWorker;
use Muchacuba\InfoSms\RechargeSubscriptionApiWorker;
use Muchacuba\InfoSms\Profile\InsufficientBalanceApiException;
use Muchacuba\InfoSms\NonExistentTopicApiException;
use Muchacuba\InfoSms\Subscription\NoResellPackageApiException;
use Muchacuba\InfoSms\NoTopicsApiException;
use Muchacuba\InfoSms\Subscription\TrialNotAcceptedApiException;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class RechargeSubscriptionController
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var RechargeSubscriptionApiWorker
     */
    private $rechargeSubscriptionApiWorker;

    /**
     * @var CollectSubscriptionsApiWorker
     */
    private $collectSubscriptionsApiWorker;

    /**
     * @param TokenStorage                  $tokenStorage
     * @param RechargeSubscriptionApiWorker $rechargeSubscriptionApiWorker
     * @param CollectSubscriptionsApiWorker $collectSubscriptionsApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"                  = @DI\Inject("security.token_storage"),
     *     "rechargeSubscriptionApiWorker" = @DI\Inject("muchacuba.info_sms.recharge_subscription_api_worker"),
     *     "collectSubscriptionsApiWorker" = @DI\Inject("muchacuba.info_sms.collect_subscriptions_api_worker"),
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        RechargeSubscriptionApiWorker $rechargeSubscriptionApiWorker,
        CollectSubscriptionsApiWorker $collectSubscriptionsApiWorker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->rechargeSubscriptionApiWorker = $rechargeSubscriptionApiWorker;
        $this->collectSubscriptionsApiWorker = $collectSubscriptionsApiWorker;
    }

    /**
     * @Req\Route("/info-sms/me/recharge-subscription/{mobile}")
     * @Req\Method({"POST"})
     *
     * @param string  $mobile
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function rechargeAction($mobile, Request $request)
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        $data = $request->request->all();

        foreach (array('topics', 'resellPackage') as $key) {
            Assertion::keyExists($data, $key);
        }

        try {
            $this->rechargeSubscriptionApiWorker->recharge(
                $mobile,
                $uniqueness,
                $data['topics'],
                $data['resellPackage']
            );
        } catch (NoTopicsApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.NO_TOPICS'
                ),
                400
            );
        } catch (NonExistentTopicApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.NON_EXISTENT_TOPIC'
                ),
                400
            );
        } catch (NoResellPackageApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.NO_RESELL_PACKAGE'
                ),
                400
            );
        } catch (InsufficientBalanceApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.INSUFFICIENT_BALANCE'
                ),
                400
            );
        } catch (TrialNotAcceptedApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.TRIAL_NOT_ACCEPTED'
                ),
                400
            );
        }

        return new JsonResponse(
            $this->collectSubscriptionsApiWorker->collect($uniqueness)
        );
    }
}
