<?php

namespace Muchacuba\ModelBundle\Controller\RechargeCard;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\RechargeCard\DeletePackageApiWorker;
use Muchacuba\RechargeCard\CollectPackagesApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DeletePackageController
{
    /**
     * @var DeletePackageApiWorker
     */
    private $deletePackageApiWorker;

    /**
     * @var CollectPackagesApiWorker
     */
    private $collectPackagesApiWorker;
    /**
     * @param DeletePackageApiWorker $deletePackageApiWorker
     * @param CollectPackagesApiWorker $collectPackagesApiWorker
     *
     * @DI\InjectParams({
     *     "deletePackageApiWorker" = @DI\Inject("muchacuba.recharge_card.delete_package_api_worker"),
     *     "collectPackagesApiWorker" = @DI\Inject("muchacuba.recharge_card.collect_packages_api_worker"),
     * })
     */
    function __construct(
        DeletePackageApiWorker $deletePackageApiWorker,
        CollectPackagesApiWorker $collectPackagesApiWorker
    )
    {
        $this->deletePackageApiWorker = $deletePackageApiWorker;
        $this->collectPackagesApiWorker = $collectPackagesApiWorker;
    }

    /**
     * @Req\Route("/recharge-card/delete-package/{id}")
     * @Req\Method({"POST"})
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        $this->deletePackageApiWorker->delete($id);

        return new JsonResponse(
            $this->collectPackagesApiWorker->collect()
        );
    }
}