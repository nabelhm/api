<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Me;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectSubscriptionsApiWorker;
use Muchacuba\InfoSms\CreateSubscriptionApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CreateSubscriptionController extends AbstractCreateSubscriptionController
{
    /**
     * @var CollectSubscriptionsApiWorker
     */
    private $collectSubscriptionsApiWorker;

    /**
     * @param TokenStorage                  $tokenStorage
     * @param CreateSubscriptionApiWorker   $createSubscriptionApiWorker
     * @param CollectSubscriptionsApiWorker $collectSubscriptionsApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"                  = @DI\Inject("security.token_storage"),
     *     "createSubscriptionApiWorker"   = @DI\Inject("muchacuba.info_sms.create_subscription_api_worker"),
     *     "collectSubscriptionsApiWorker" = @DI\Inject("muchacuba.info_sms.collect_subscriptions_api_worker"),
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        CreateSubscriptionApiWorker $createSubscriptionApiWorker,
        CollectSubscriptionsApiWorker $collectSubscriptionsApiWorker
    )
    {
        parent::__construct(
            $tokenStorage,
            $createSubscriptionApiWorker
        );

        $this->collectSubscriptionsApiWorker = $collectSubscriptionsApiWorker;
    }

    /**
     * @Req\Route("/info-sms/me/create-subscription")
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

        return new JsonResponse(
            $this->collectSubscriptionsApiWorker->collect($uniqueness)
        );
    }
}
