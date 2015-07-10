<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\Subscription\ComputeOperationsFromTopicUntilDateApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ComputeOperationsFromTopicUntilDateController
{
    /**
     * @var ComputeOperationsFromTopicUntilDateApiWorker
     */
    private $computeOperationsFromTopicUntilDateApiWorker;

    /**
     * @param ComputeOperationsFromTopicUntilDateApiWorker $computeOperationsFromTopicUntilDateApiWorker
     *
     * @DI\InjectParams({
     *     "computeOperationsFromTopicUntilDateApiWorker" = @DI\Inject("muchacuba.info_sms.subscription.compute_operations_from_topic_until_date_api_worker"),
     * })
     */
    function __construct(
        ComputeOperationsFromTopicUntilDateApiWorker $computeOperationsFromTopicUntilDateApiWorker
    )
    {
        $this->computeOperationsFromTopicUntilDateApiWorker = $computeOperationsFromTopicUntilDateApiWorker;
    }

    /**
     * @param string $topic
     * @param string $until
     *
     * @Req\Route("/info-sms/subscription/{topic}/{until}/compute-operations-from-topic-until-date")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function computeAction($topic, $until)
    {
        return new JsonResponse(
            $this->computeOperationsFromTopicUntilDateApiWorker->compute($topic, $until)
        );
    }
}
