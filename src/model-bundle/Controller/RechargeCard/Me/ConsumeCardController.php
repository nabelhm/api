<?php

namespace Muchacuba\ModelBundle\Controller\RechargeCard\Me;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\Credit\PickProfileApiWorker as PickCreditProfileApiWorker;
use Muchacuba\RechargeCard\Card\AlreadyConsumedApiException;
use Muchacuba\RechargeCard\Card\NonExistentCodeApiException;
use Muchacuba\RechargeCard\ConsumeCardApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ConsumeCardController
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var ConsumeCardApiWorker
     */
    private $consumeCardApiWorker;

    /**
     * @var PickCreditProfileApiWorker
     */
    private $pickCreditProfileApiWorker;

    /**
     * @param TokenStorage               $tokenStorage
     * @param ConsumeCardApiWorker       $consumeCardApiWorker
     * @param PickCreditProfileApiWorker $pickCreditProfileApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"               = @DI\Inject("security.token_storage"),
     *     "consumeCardApiWorker"       = @DI\Inject("muchacuba.recharge_card.consume_card_api_worker"),
     *     "pickCreditProfileApiWorker" = @DI\Inject("muchacuba.credit.pick_profile_api_worker")
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        ConsumeCardApiWorker $consumeCardApiWorker,
        PickCreditProfileApiWorker $pickCreditProfileApiWorker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->consumeCardApiWorker = $consumeCardApiWorker;
        $this->pickCreditProfileApiWorker = $pickCreditProfileApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/me/consume-card")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function consumeAction(Request $request)
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        $data = $request->request->all();

        foreach (array('code') as $key) {
            Assertion::keyExists($data, $key);
        }

        try {
            $this->consumeCardApiWorker->consume(
                $uniqueness,
                $data['code']
            );
        } catch (NonExistentCodeApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'RECHARGE_CARD.CARD.NON_EXISTENT_CODE'
                ),
                400
            );
        } catch (AlreadyConsumedApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'RECHARGE_CARD.CARD.ALREADY_CONSUMED'
                ),
                400
            );
        }

        return new JsonResponse(
            $this->pickCreditProfileApiWorker->pick($uniqueness)
        );
    }
}