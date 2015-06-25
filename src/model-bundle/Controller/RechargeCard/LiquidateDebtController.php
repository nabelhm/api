<?php


namespace Muchacuba\ModelBundle\Controller\RechargeCard;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\RechargeCard\LiquidateDebtApiWorker;
use Muchacuba\RechargeCard\Profile\GreaterThanRealDebtApiException;
use Muchacuba\RechargeCard\Profile\InvalidAmountApiException;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class LiquidateDebtController
{
    /**
     * @var LiquidateDebtApiWorker
     */
    private $liquidateDebtApiWorker;

    /**
     * @param LiquidateDebtApiWorker $liquidateDebtApiWorker
     *
     * @DI\InjectParams({
     *     "liquidateDebtApiWorker" = @DI\Inject("muchacuba.recharge_card.liquidate_debt_api_worker"),
     * })
     */
    function __construct(
        LiquidateDebtApiWorker $liquidateDebtApiWorker
    )
    {
        $this->liquidateDebtApiWorker = $liquidateDebtApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/liquidate-debt")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function liquidateAction(Request $request)
    {
        $data = $request->request->all();

        foreach (array('uniqueness', 'amount') as $key) {
            Assertion::keyExists($data, $key);
        }

        try {
            $this->liquidateDebtApiWorker->liquidate(
                $data['uniqueness'],
                $data['amount']
            );
        } catch (InvalidAmountApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'RECHARGE_CARD.PROFILE.INVALID_AMOUNT'
                ),
                400
            );
        } catch (GreaterThanRealDebtApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'RECHARGE_CARD.PROFILE.GREATER_THAN_REAL_DEBT'
                ),
                400
            );
        }

        return new JsonResponse();
    }
}