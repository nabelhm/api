<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Message;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\Message\CollectLatestStatsApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectLatestStatsController
{
    /**
     * @var CollectLatestStatsApiWorker
     */
    private $collectLatestStatsApiWorker;

    /**
     * @param CollectLatestStatsApiWorker $collectLatestStatsApiWorker
     *
     * @DI\InjectParams({
     *     "collectLatestStatsApiWorker" = @DI\Inject("muchacuba.info_sms.message.collect_latest_stats_api_worker")
     * })
     */
    function __construct(
        CollectLatestStatsApiWorker $collectLatestStatsApiWorker
    )
    {
        $this->collectLatestStatsApiWorker = $collectLatestStatsApiWorker;
    }

    /**
     * @Req\Route("/info-sms/message/collect-latest-stats")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function collectAction()
    {
        return new JsonResponse(
            $this->collectLatestStatsApiWorker->collect()
        );
    }
}