<?php

namespace Muchacuba\ModelBundle\Controller\RechargeCard;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\RechargeCard\CollectCategoriesApiWorker;
use Muchacuba\RechargeCard\CreateCategoryApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CreateCategoryController
{
    /**
     * @var CreateCategoryApiWorker
     */
    private $createCategoryApiWorker;

    /**
     * @var CollectCategoriesApiWorker
     */
    private $collectCategoriesApiWorker;

    /**
     * @param CreateCategoryApiWorker    $createCategoryApiWorker
     * @param CollectCategoriesApiWorker $collectCategoriesApiWorker
     *
     * @DI\InjectParams({
     *     "createCategoryApiWorker"     = @DI\Inject("muchacuba.recharge_card.create_category_api_worker"),
     *     "collectCategoriesApiWorker"  = @DI\Inject("muchacuba.recharge_card.collect_categories_api_worker"),
     * })
     */
    function __construct(
        CreateCategoryApiWorker    $createCategoryApiWorker,
        CollectCategoriesApiWorker $collectCategoriesApiWorker
    )
    {
        $this->createCategoryApiWorker = $createCategoryApiWorker;
        $this->collectCategoriesApiWorker = $collectCategoriesApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/create-category", name="muchacuba.recharge_card.create-category")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();

        foreach (array('name', 'utility') as $key) {
            Assertion::keyExists($data, $key);
        }

        $this->createCategoryApiWorker->create(
            $data['name'],
            $data['utility']
        );

        return new JsonResponse(
            $this->collectCategoriesApiWorker->collect()
        );
    }
}
