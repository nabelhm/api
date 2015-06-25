<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Info;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\Info\CollectByTopicStatsFromCurrentWeekApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectByTopicStatsFromCurrentWeekController
{
//    /**
//     * @var CollectByTopicStatsFromCurrentWeekApiWorker
//     */
//    private $collectByTopicStatsFromCurrentWeekApiWorker;
//
//    /**
//     * @param CollectByTopicStatsFromCurrentWeekApiWorker $collectByTopicStatsFromCurrentWeekApiWorker
//     *
//     * @DI\InjectParams({
//     *     "collectByTopicStatsFromCurrentWeekApiWorker" = @DI\Inject("muchacuba.info_sms.info.collect_by_topic_stats_from_current_week_api_worker"),
//     * })
//     */
//    function __construct(
//        CollectByTopicStatsFromCurrentWeekApiWorker $collectByTopicStatsFromCurrentWeekApiWorker
//    )
//    {
//        $this->collectByTopicStatsFromCurrentWeekApiWorker = $collectByTopicStatsFromCurrentWeekApiWorker;
//    }
//
//    /**
//     * @param string $topic
//     *
//     * @Req\Route("/info-sms/info/{topic}/collect-by-topic-stats-from-current-week")
//     * @Req\Method({"GET"})
//     *
//     * @return JsonResponse
//     */
//    public function collectFromCurrentWeekAction($topic)
//    {
//        return new JsonResponse(
//            $this->collectByTopicStatsFromCurrentWeekApiWorker->collect($topic)
//        );
//    }
}
