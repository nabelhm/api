<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Me;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\BuyPackageApiWorker;
use Muchacuba\InfoSms\Profile\InsufficientBalanceApiException;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Muchacuba\InfoSms\PickProfileApiWorker as PickInfoSmsProfileApiWorker;
use Muchacuba\Credit\PickProfileApiWorker as PickCreditProfileApiWorker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class BuyPackageController
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var BuyPackageApiWorker
     */
    private $buyPackageApiWorker;

    /**
     * @var PickInfoSmsProfileApiWorker
     */
    private $pickInfoSmsProfileApiWorker;

    /**
     * @var PickCreditProfileApiWorker
     */
    private $pickCreditProfileApiWorker;

    /**
     * @param TokenStorage                $tokenStorage
     * @param BuyPackageApiWorker         $buyPackageApiWorker
     * @param PickInfoSmsProfileApiWorker $pickInfoSmsProfileApiWorker
     * @param PickCreditProfileApiWorker  $pickCreditProfileApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"                = @DI\Inject("security.token_storage"),
     *     "buyPackageApiWorker"         = @DI\Inject("muchacuba.info_sms.buy_package_api_worker"),
     *     "pickInfoSmsProfileApiWorker" = @DI\Inject("muchacuba.info_sms.pick_profile_api_worker"),
     *     "pickCreditProfileApiWorker"  = @DI\Inject("muchacuba.credit.pick_profile_api_worker"),
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        BuyPackageApiWorker $buyPackageApiWorker,
        PickInfoSmsProfileApiWorker $pickInfoSmsProfileApiWorker,
        PickCreditProfileApiWorker $pickCreditProfileApiWorker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->buyPackageApiWorker = $buyPackageApiWorker;
        $this->pickInfoSmsProfileApiWorker = $pickInfoSmsProfileApiWorker;
        $this->pickCreditProfileApiWorker = $pickCreditProfileApiWorker;
    }

    /**
     * @Req\Route("/info-sms/me/buy-package")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function buyAction(Request $request)
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        $data = $request->request->all();

        foreach (array('id') as $key) {
            Assertion::keyExists($data, $key);
        }

        try {
            $this->buyPackageApiWorker->buy(
                $uniqueness,
                $data['id']
            );
        } catch (InsufficientBalanceApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.PROFILE.INSUFFICIENT_BALANCE'
                ),
                400
            );
        }

        return new JsonResponse([
            'infoSmsProfile' => $this->pickInfoSmsProfileApiWorker->pick($uniqueness),
            'creditProfile' => $this->pickCreditProfileApiWorker->pick($uniqueness)
        ]);
    }
}
