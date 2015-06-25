<?php

namespace Muchacuba\ModelBundle\Controller\RechargeCard\Me\Profile\Debt;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\RechargeCard\Profile\Debt\CollectOperationsApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectOperationsController
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var CollectOperationsApiWorker
     */
    private $collectOperationsApiWorker;

    /**
     * @param TokenStorage               $tokenStorage
     * @param CollectOperationsApiWorker $collectOperationsApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"               = @DI\Inject("security.token_storage"),
     *     "collectOperationsApiWorker" = @DI\Inject("muchacuba.recharge_card.profile.debt.collect_operations_api_worker"),
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        CollectOperationsApiWorker $collectOperationsApiWorker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->collectOperationsApiWorker = $collectOperationsApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/me/profile/debt/collect-operations")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function collectAction()
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        return new JsonResponse(
            $this->collectOperationsApiWorker->collect($uniqueness)
        );
    }
}
