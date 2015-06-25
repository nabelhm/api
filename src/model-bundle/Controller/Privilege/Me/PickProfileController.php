<?php

namespace Muchacuba\ModelBundle\Controller\Privilege\Me;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\Privilege\PickProfileApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class PickProfileController
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var PickProfileApiWorker
     */
    private $pickProfileApiWorker;

    /**
     * @param TokenStorage         $tokenStorage
     * @param PickProfileApiWorker $pickProfileApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"         = @DI\Inject("security.token_storage"),
     *     "pickProfileApiWorker" = @DI\Inject("muchacuba.privilege.pick_profile_api_worker"),
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        PickProfileApiWorker $pickProfileApiWorker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->pickProfileApiWorker = $pickProfileApiWorker;
    }

    /**
     * @Req\Route("/privilege/me/pick-profile")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function pickAction()
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        $profile = $this->pickProfileApiWorker->pick($uniqueness);

        return new JsonResponse(
            $profile
        );
    }
}