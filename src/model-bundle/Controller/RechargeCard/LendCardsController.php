<?php

namespace Muchacuba\ModelBundle\Controller\RechargeCard;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\RechargeCard\LendCardsApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class LendCardsController
{
    /**
     * @var LendCardsApiWorker
     */
    private $lendCardsApiWorker;

    /**
     * @param LendCardsApiWorker $lendCardsApiWorker
     *
     * @DI\InjectParams({
     *     "lendCardsApiWorker" = @DI\Inject("muchacuba.recharge_card.lend_cards_api_worker"),
     * })
     */
    function __construct(
        LendCardsApiWorker $lendCardsApiWorker
    )
    {
        $this->lendCardsApiWorker = $lendCardsApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/lend-cards")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function lendAction(Request $request)
    {
        $data = $request->request->all();

        foreach (array('uniqueness', 'package') as $key) {
            Assertion::keyExists($data, $key);
        }

        $this->lendCardsApiWorker->lend(
            $data['uniqueness'],
            $data['package']
        );

        return new JsonResponse();
    }
}