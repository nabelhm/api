<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\Subscription\ComputeOperationsFromTopicBetweenDatesApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ComputeOperationsFromTopicBetweenDatesController
{
    /**
     * @var ComputeOperationsFromTopicBetweenDatesApiWorker
     */
    private $computeOperationsFromTopicBetweenDatesApiWorker;

    /**
     * @param ComputeOperationsFromTopicBetweenDatesApiWorker $computeOperationsFromTopicBetweenDatesApiWorker
     *
     * @DI\InjectParams({
     *     "computeOperationsFromTopicBetweenDatesApiWorker" = @DI\Inject("muchacuba.info_sms.subscription.compute_operations_from_topic_between_dates_api_worker"),
     * })
     */
    function __construct(
        ComputeOperationsFromTopicBetweenDatesApiWorker $computeOperationsFromTopicBetweenDatesApiWorker
    )
    {
        $this->computeOperationsFromTopicBetweenDatesApiWorker = $computeOperationsFromTopicBetweenDatesApiWorker;
    }

    /**
     * @param string $topic
     * @param string $from
     * @param string $to
     * @param int    $group
     *
     * @Req\Route("/info-sms/subscription/{topic}/{from}/{to}/{group}/compute-operations-from-topic-between-dates")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function computeAction($topic, $from, $to, $group)
    {
        return new JsonResponse(
            $this->computeOperationsFromTopicBetweenDatesApiWorker->compute($topic, $from, $to, $group)
        );
    }
}
