<?php

namespace Muchacuba\ModelBundle\Controller\Credit\Me\Profile\Balance;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\Credit\Profile\Balance\CollectOperationsApiWorker;
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
     *     "collectOperationsApiWorker" = @DI\Inject("muchacuba.credit.profile.balance.collect_operations_api_worker"),
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
     * @Req\Route("/credit/me/profile/balance/collect-operations")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function collectByUniquenessAction()
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        return new JsonResponse(
            $this->collectOperationsApiWorker->collect($uniqueness)
        );
    }
}
