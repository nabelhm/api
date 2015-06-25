<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectResellPackagesApiWorker;
use Muchacuba\InfoSms\DeleteResellPackageApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DeleteResellPackageController
{
    /**
     * @var DeleteResellPackageApiWorker
     */
    private $deleteResellPackageApiWorker;

    /**
     * @param DeleteResellPackageApiWorker   $deleteResellPackageApiWorker
     * @param CollectResellPackagesApiWorker $collectResellPackagesApiWorker
     *
     * @DI\InjectParams({
     *     "deleteResellPackageApiWorker"   = @DI\Inject("muchacuba.info_sms.delete_resell_package_api_worker"),
     *     "collectResellPackagesApiWorker" = @DI\Inject("muchacuba.info_sms.collect_resell_packages_api_worker"),
     * })
     */
    function __construct(
        DeleteResellPackageApiWorker $deleteResellPackageApiWorker,
        CollectResellPackagesApiWorker $collectResellPackagesApiWorker
    )
    {
        $this->deleteResellPackageApiWorker = $deleteResellPackageApiWorker;
        $this->collectResellPackagesApiWorker = $collectResellPackagesApiWorker;
    }

    /**
     * @Req\Route("/info-sms/delete-resell-package/{id}")
     * @Req\Method({"POST"})
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        $this->deleteResellPackageApiWorker->delete($id);

        return new JsonResponse(
            $this->collectResellPackagesApiWorker->collect()
        );
    }
}