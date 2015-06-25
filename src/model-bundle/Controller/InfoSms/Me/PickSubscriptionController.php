<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Me;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\PickSubscriptionApiWorker;
use Muchacuba\InfoSms\Subscription\NonExistentMobileAndUniquenessApiException;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class PickSubscriptionController
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var PickSubscriptionApiWorker
     */
    private $pickSubscriptionApiWorker;

    /**
     * @param TokenStorage              $tokenStorage
     * @param PickSubscriptionApiWorker $pickSubscriptionApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"              = @DI\Inject("security.token_storage"),
     *     "pickSubscriptionApiWorker" = @DI\Inject("muchacuba.info_sms.pick_subscription_api_worker"),
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        PickSubscriptionApiWorker $pickSubscriptionApiWorker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->pickSubscriptionApiWorker = $pickSubscriptionApiWorker;
    }

    /**
     * @Req\Route("/info-sms/me/pick-subscription/{mobile}")
     * @Req\Method({"GET"})
     *
     * @param string $mobile
     *
     * @return JsonResponse
     */
    public function pickAction($mobile)
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        try {
            $subscription = $this->pickSubscriptionApiWorker->pick($mobile, $uniqueness);
        } catch (NonExistentMobileAndUniquenessApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.NON_EXISTENT_MOBILE_AND_UNIQUENESS'
                ),
                400
            );
        }

        return new JsonResponse(
            $subscription
        );
    }
}