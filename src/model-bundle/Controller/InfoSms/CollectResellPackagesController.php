<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectResellPackagesApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectResellPackagesController
{
    /**
     * @var CollectResellPackagesApiWorker
     */
    private $collectResellPackagesApiWorker;

    /**
     * @param CollectResellPackagesApiWorker $collectResellPackagesApiWorker
     *
     * @DI\InjectParams({
     *     "collectResellPackagesApiWorker" = @DI\Inject("muchacuba.info_sms.collect_resell_packages_api_worker"),
     * })
     */
    function __construct(
        CollectResellPackagesApiWorker $collectResellPackagesApiWorker
    )
    {
        $this->collectResellPackagesApiWorker = $collectResellPackagesApiWorker;
    }

    /**
     * @Req\Route("/info-sms/collect-resell-packages")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function collectAction()
    {
        return new JsonResponse(
            $this->collectResellPackagesApiWorker->collect()
        );
    }
}