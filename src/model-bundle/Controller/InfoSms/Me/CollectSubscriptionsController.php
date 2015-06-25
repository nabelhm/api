<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Me;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectSubscriptionsApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectSubscriptionsController
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var CollectSubscriptionsApiWorker
     */
    private $collectSubscriptionsApiWorker;

    /**
     * @param TokenStorage                  $tokenStorage
     * @param CollectSubscriptionsApiWorker $collectSubscriptionsApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"                  = @DI\Inject("security.token_storage"),
     *     "collectSubscriptionsApiWorker" = @DI\Inject("muchacuba.info_sms.collect_subscriptions_api_worker"),
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        CollectSubscriptionsApiWorker $collectSubscriptionsApiWorker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->collectSubscriptionsApiWorker = $collectSubscriptionsApiWorker;
    }

    /**
     * @Req\Route("/info-sms/me/collect-subscriptions")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function collectAction()
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        $subscriptions = $this->collectSubscriptionsApiWorker->collect($uniqueness);

        return new JsonResponse(
            $subscriptions
        );
    }
}
