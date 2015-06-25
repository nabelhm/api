<?php

namespace Muchacuba\ModelBundle\Controller\RechargeCard;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\RechargeCard\CollectPackagesApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectPackagesController
{
    /**
     * @var CollectPackagesApiWorker
     */
    private $collectPackagesApiWorker;

    /**
     * @param CollectPackagesApiWorker $collectPackagesApiWorker
     *
     * @DI\InjectParams({
     *     "collectPackagesApiWorker" = @DI\Inject("muchacuba.recharge_card.collect_packages_api_worker"),
     * })
     */
    function __construct(
        CollectPackagesApiWorker $collectPackagesApiWorker
    )
    {
        $this->collectPackagesApiWorker = $collectPackagesApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/collect-packages")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function collectAction()
    {
        return new JsonResponse(
            $this->collectPackagesApiWorker->collect()
        );
    }
}
