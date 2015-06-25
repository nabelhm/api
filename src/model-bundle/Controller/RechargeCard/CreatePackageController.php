<?php

namespace Muchacuba\ModelBundle\Controller\RechargeCard;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\RechargeCard\CollectPackagesApiWorker;
use Muchacuba\RechargeCard\CreatePackageApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CreatePackageController
{
    /**
     * @var CreatePackageApiWorker
     */
    private $createPackageApiWorker;

    /**
     * @var CollectPackagesApiWorker
     */
    private $collectPackagesApiWorker;

    /**
     * @param CreatePackageApiWorker   $createPackageApiWorker
     * @param CollectPackagesApiWorker $collectPackagesApiWorker
     *
     * @DI\InjectParams({
     *     "createPackageApiWorker"   = @DI\Inject("muchacuba.recharge_card.create_package_api_worker"),
     *     "collectPackagesApiWorker" = @DI\Inject("muchacuba.recharge_card.collect_packages_api_worker"),
     * })
     */
    function __construct(
        CreatePackageApiWorker $createPackageApiWorker,
        CollectPackagesApiWorker $collectPackagesApiWorker
    )
    {
        $this->createPackageApiWorker = $createPackageApiWorker;
        $this->collectPackagesApiWorker = $collectPackagesApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/create-package", name="muchacuba.recharge_card.create-package")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();

        foreach (array('name', 'category', 'amount', 'price') as $key) {
            Assertion::keyExists($data, $key);
        }

        $this->createPackageApiWorker->create(
            $data['name'],
            $data['category'],
            $data['amount'],
            $data['price']
        );

        return new JsonResponse(
            $this->collectPackagesApiWorker->collect()
        );
    }
}
