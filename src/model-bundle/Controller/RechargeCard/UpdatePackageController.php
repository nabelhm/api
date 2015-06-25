<?php

namespace Muchacuba\ModelBundle\Controller\RechargeCard;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\RechargeCard\CollectPackagesApiWorker;
use Muchacuba\RechargeCard\UpdatePackageApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UpdatePackageController
{
    /**
     * @var UpdatePackageApiWorker
     */
    private $updatePackageApiWorker;

    /**
     * @var CollectPackagesApiWorker
     */
    private $collectPackagesApiWorker;

    /**
     * @param UpdatePackageApiWorker   $updatePackageApiWorker
     * @param CollectPackagesApiWorker $collectPackagesApiWorker
     *
     * @DI\InjectParams({
     *     "updatePackageApiWorker"   = @DI\Inject("muchacuba.recharge_card.update_package_api_worker"),
     *     "collectPackagesApiWorker" = @DI\Inject("muchacuba.recharge_card.collect_packages_api_worker"),
     * })
     */
    function __construct(
        UpdatePackageApiWorker $updatePackageApiWorker,
        CollectPackagesApiWorker $collectPackagesApiWorker
    )
    {
        $this->updatePackageApiWorker = $updatePackageApiWorker;
        $this->collectPackagesApiWorker = $collectPackagesApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/update-package/{id}")
     * @Req\Method({"POST"})
     *
     * @param string $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateAction($id, Request $request)
    {
        $data = $request->request->all();

        foreach (array('name', 'category', 'amount', 'price') as $key) {
            Assertion::keyExists($data, $key);
        }

        $this->updatePackageApiWorker->update(
            $id,
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