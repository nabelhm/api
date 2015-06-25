<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Info;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\Info\CollectByTopicStatsFromCurrentYearApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectByTopicStatsFromCurrentYearController
{
//    /**
//     * @var CollectByTopicStatsFromCurrentYearApiWorker
//     */
//    private $collectByTopicStatsFromCurrentYearApiWorker;
//
//    /**
//     * @param CollectByTopicStatsFromCurrentYearApiWorker $collectByTopicStatsFromCurrentYearApiWorker
//     *
//     * @DI\InjectParams({
//     *     "collectByTopicStatsFromCurrentYearApiWorker" = @DI\Inject("muchacuba.info_sms.info.collect_by_topic_stats_from_current_year_api_worker"),
//     * })
//     */
//    function __construct(
//        CollectByTopicStatsFromCurrentYearApiWorker $collectByTopicStatsFromCurrentYearApiWorker
//    )
//    {
//        $this->collectByTopicStatsFromCurrentYearApiWorker = $collectByTopicStatsFromCurrentYearApiWorker;
//    }
//
//    /**
//     * @param string $topic
//     *
//     * @Req\Route("/info-sms/info/{topic}/collect-by-topic-stats-from-current-year")
//     * @Req\Method({"GET"})
//     *
//     * @return JsonResponse
//     */
//    public function collectAction($topic)
//    {
//        return new JsonResponse(
//            $this->collectByTopicStatsFromCurrentYearApiWorker->collect($topic)
//        );
//    }
}
