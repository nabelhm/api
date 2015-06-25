<?php

namespace Muchacuba\ModelBundle\Controller\RechargeCard;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\RechargeCard\CollectCategoriesApiWorker;
use Muchacuba\RechargeCard\DeleteCategoryApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DeleteCategoryController
{
    /**
     * @var DeleteCategoryApiWorker
     */
    private $deleteCategoryApiWorker;

    /**
     * @var CollectCategoriesApiWorker
     */
    private $collectCategoriesApiWorker;
    /**
     * @param DeleteCategoryApiWorker $deleteCategoryApiWorker
     * @param CollectCategoriesApiWorker $collectCategoriesApiWorker
     *
     * @DI\InjectParams({
     *     "deleteCategoryApiWorker" = @DI\Inject("muchacuba.recharge_card.delete_category_api_worker"),
     *     "collectCategoriesApiWorker" = @DI\Inject("muchacuba.recharge_card.collect_categories_api_worker"),
     * })
     */
    function __construct(
        DeleteCategoryApiWorker $deleteCategoryApiWorker,
        CollectCategoriesApiWorker $collectCategoriesApiWorker
    )
    {
        $this->deleteCategoryApiWorker = $deleteCategoryApiWorker;
        $this->collectCategoriesApiWorker = $collectCategoriesApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/delete-category/{id}", name="muchacuba.recharge_card.delete_category")
     * @Req\Method({"POST"})
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        $this->deleteCategoryApiWorker->delete($id);

        return new JsonResponse(
            $this->collectCategoriesApiWorker->collect()
        );
    }
}