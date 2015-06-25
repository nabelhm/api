<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Me;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\ComputeSubscriptionsApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ComputeSubscriptionsController
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var ComputeSubscriptionsApiWorker
     */
    private $computeSubscriptionsApiWorker;

    /**
     * @param TokenStorage                  $tokenStorage
     * @param ComputeSubscriptionsApiWorker $computeSubscriptionsApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"                  = @DI\Inject("security.token_storage"),
     *     "computeSubscriptionsApiWorker" = @DI\Inject("muchacuba.info_sms.compute_subscriptions_api_worker"),
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        ComputeSubscriptionsApiWorker $computeSubscriptionsApiWorker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->computeSubscriptionsApiWorker = $computeSubscriptionsApiWorker;
    }

    /**
     * @Req\Route("/info-sms/me/compute-subscriptions")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function computeAction()
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        $amount = $this->computeSubscriptionsApiWorker->compute($uniqueness);

        return new JsonResponse(
            $amount
        );
    }
}
