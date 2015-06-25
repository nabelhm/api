<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectTopicsApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectTopicsController
{
    /**
     * @var CollectTopicsApiWorker
     */
    private $collectTopicsApiWorker;

    /**
     * @param CollectTopicsApiWorker $collectTopicsApiWorker
     *
     * @DI\InjectParams({
     *     "collectTopicsApiWorker" = @DI\Inject("muchacuba.info_sms.collect_topics_api_worker"),
     * })
     */
    function __construct(
        CollectTopicsApiWorker $collectTopicsApiWorker
    )
    {
        $this->collectTopicsApiWorker = $collectTopicsApiWorker;
    }

    /**
     * @Req\Route("/info-sms/collect-topics")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function collectAction()
    {
        return new JsonResponse(
            $this->collectTopicsApiWorker->collect()
        );
    }
}