<?php

namespace Muchacuba\ModelBundle\Controller\Invitation;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\Invitation\GiveCardsApiWorker;
use Muchacuba\Invitation\Profile\InvalidAmountApiException;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 */
class GiveCardsController
{
    /**
     * @var GiveCardsApiWorker
     */
    private $giveCardsApiWorker;

    /**
     * @param GiveCardsApiWorker $giveCardsApiWorker
     *
     * @DI\InjectParams({
     *     "giveCardsApiWorker" = @DI\Inject("muchacuba.invitation.give_cards_api_worker"),
     * })
     */
    function __construct(
        GiveCardsApiWorker $giveCardsApiWorker
    )
    {
        $this->giveCardsApiWorker = $giveCardsApiWorker;
    }

    /**
     * @Req\Route("/invitation/give-cards")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function giveAction(Request $request)
    {
        $data = $request->request->all();

        foreach (array('uniqueness', 'role', 'amount') as $key) {
            Assertion::keyExists($data, $key);
        }

        try {
            $this->giveCardsApiWorker->give(
                $data['uniqueness'],
                $data['role'],
                $data['amount']
            );
        } catch (InvalidAmountApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INVITATION.PROFILE.INVALID_AMOUNT'
                ),
                400
            );
        }

        return new JsonResponse();
    }
}