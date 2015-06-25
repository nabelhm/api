<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectInfosApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectInfosController
{
    /**
     * @var CollectInfosApiWorker
     */
    private $collectInfosApiWorker;

    /**
     * @param CollectInfosApiWorker $collectInfosApiWorker
     *
     * @DI\InjectParams({
     *     "collectInfosApiWorker" = @DI\Inject("muchacuba.info_sms.collect_infos_api_worker"),
     * })
     */
    function __construct(
        CollectInfosApiWorker $collectInfosApiWorker
    )
    {
        $this->collectInfosApiWorker = $collectInfosApiWorker;
    }

    /**
     * @Req\Route("/info-sms/collect-infos")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function collectAction()
    {
        return new JsonResponse(
            $this->collectInfosApiWorker->collect()
        );
    }
}