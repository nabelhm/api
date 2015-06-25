<?php

namespace Muchacuba\ModelBundle\Controller\RechargeCard;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\RechargeCard\CollectCategoriesApiWorker;
use Muchacuba\RechargeCard\UpdateCategoryApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UpdateCategoryController
{
    /**
     * @var UpdateCategoryApiWorker
     */
    private $updateCategoryApiWorker;

    /**
     * @var CollectCategoriesApiWorker
     */
    private $collectCategoriesApiWorker;

    /**
     * @param UpdateCategoryApiWorker    $updateCategoryApiWorker
     * @param CollectCategoriesApiWorker $collectCategoriesApiWorker
     *
     * @DI\InjectParams({
     *     "updateCategoryApiWorker"     = @DI\Inject("muchacuba.recharge_card.update_category_api_worker"),
     *     "collectCategoriesApiWorker"  = @DI\Inject("muchacuba.recharge_card.collect_categories_api_worker"),
     * })
     */
    function __construct(
        UpdateCategoryApiWorker $updateCategoryApiWorker,
        CollectCategoriesApiWorker $collectCategoriesApiWorker
    )
    {
        $this->updateCategoryApiWorker = $updateCategoryApiWorker;
        $this->collectCategoriesApiWorker = $collectCategoriesApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/update-category/{id}")
     * @Req\Method({"POST"})
     *
     * @param string  $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateAction($id, Request $request)
    {
        $data = $request->request->all();

        foreach (array('name', 'utility') as $key) {
            Assertion::keyExists($data, $key);
        }

        $this->updateCategoryApiWorker->update(
            $id,
            $data['name'],
            $data['utility']
        );

        return new JsonResponse(
            $this->collectCategoriesApiWorker->collect()
        );
    }
}