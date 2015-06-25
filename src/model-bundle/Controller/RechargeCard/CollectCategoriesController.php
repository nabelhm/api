<?php

namespace Muchacuba\ModelBundle\Controller\RechargeCard;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\RechargeCard\CollectCategoriesApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectCategoriesController
{
    /**
     * @var CollectCategoriesApiWorker
     */
    private $collectCategoriesApiWorker;

    /**
     * @param CollectCategoriesApiWorker $collectCategoriesApiWorker
     *
     * @DI\InjectParams({
     *     "collectCategoriesApiWorker" = @DI\Inject("muchacuba.recharge_card.collect_categories_api_worker"),
     * })
     */
    function __construct(
        CollectCategoriesApiWorker $collectCategoriesApiWorker
    )
    {
        $this->collectCategoriesApiWorker = $collectCategoriesApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/collect-categories")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function collectAction()
    {
        return new JsonResponse(
            $this->collectCategoriesApiWorker->collect()
        );
    }
}