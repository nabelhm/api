<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Me;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\ComputeSubscriptionsApiWorker;
use Muchacuba\InfoSms\CreateSubscriptionApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Muchacuba\InfoSms\PickProfileApiWorker as PickInfoSmsProfileApiWorker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CreateSubscriptionAndComputeController extends AbstractCreateSubscriptionController
{
    /**
     * @var ComputeSubscriptionsApiWorker
     */
    private $computeSubscriptionsApiWorker;

    /**
     * @var PickInfoSmsProfileApiWorker
     */
    private $pickInfoSmsProfileApiWorker;

    /**
     * @param TokenStorage                  $tokenStorage
     * @param CreateSubscriptionApiWorker   $createSubscriptionApiWorker
     * @param ComputeSubscriptionsApiWorker $computeSubscriptionsApiWorker
     * @param PickInfoSmsProfileApiWorker   $pickInfoSmsProfileApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"                  = @DI\Inject("security.token_storage"),
     *     "createSubscriptionApiWorker"   = @DI\Inject("muchacuba.info_sms.create_subscription_api_worker"),
     *     "computeSubscriptionsApiWorker" = @DI\Inject("muchacuba.info_sms.compute_subscriptions_api_worker"),
     *     "pickInfoSmsProfileApiWorker"   = @DI\Inject("muchacuba.info_sms.pick_profile_api_worker")
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        CreateSubscriptionApiWorker $createSubscriptionApiWorker,
        ComputeSubscriptionsApiWorker $computeSubscriptionsApiWorker,
        PickInfoSmsProfileApiWorker $pickInfoSmsProfileApiWorker
    )
    {
        parent::__construct(
            $tokenStorage,
            $createSubscriptionApiWorker
        );

        $this->computeSubscriptionsApiWorker = $computeSubscriptionsApiWorker;
        $this->pickInfoSmsProfileApiWorker = $pickInfoSmsProfileApiWorker;
    }

    /**
     * @Req\Route("/info-sms/me/create-subscription-and-compute")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        $response = $this->create($request);

        if ($response->getStatusCode() != '200') {
            return $response;
        }

        return new JsonResponse([
            'subscriptionsAmount' => $this->computeSubscriptionsApiWorker->compute($uniqueness),
            'infoSmsProfile' => $this->pickInfoSmsProfileApiWorker->pick($uniqueness)
        ]);
    }
}
