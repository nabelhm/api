<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Message;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\Message\ComputeStatsFromTopicBetweenDatesApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ComputeStatsFromTopicBetweenDatesController
{
    /**
     * @var ComputeStatsFromTopicBetweenDatesApiWorker
     */
    private $computeStatsFromTopicBetweenDatesApiWorker;

    /**
     * @param ComputeStatsFromTopicBetweenDatesApiWorker $computeStatsFromTopicBetweenDatesApiWorker
     *
     * @DI\InjectParams({
     *     "computeStatsFromTopicBetweenDatesApiWorker" = @DI\Inject("muchacuba.info_sms.message.compute_stats_from_topic_between_dates_api_worker"),
     * })
     */
    function __construct(
        ComputeStatsFromTopicBetweenDatesApiWorker $computeStatsFromTopicBetweenDatesApiWorker
    )
    {
        $this->computeStatsFromTopicBetweenDatesApiWorker = $computeStatsFromTopicBetweenDatesApiWorker;
    }

    /**
     * @param string $topic
     * @param string $from
     * @param string $to
     * @param int    $group
     *
     * @Req\Route("/info-sms/message/{topic}/{from}/{to}/{group}/compute-stats-from-topic-between-dates")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function computeAction($topic, $from, $to, $group)
    {
        return new JsonResponse(
            $this->computeStatsFromTopicBetweenDatesApiWorker->compute($topic, $from, $to, $group)
        );
    }
}
