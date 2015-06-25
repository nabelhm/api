<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\Subscription\CollectOperationsByTopicFromCurrentWeekApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectOperationsByTopicFromCurrentWeekController
{
//    /**
//     * @var CollectOperationsByTopicFromCurrentWeekApiWorker
//     */
//    private $collectOperationsByTopicFromCurrentWeekApiWorker;
//
//    /**
//     * @param CollectOperationsByTopicFromCurrentWeekApiWorker $collectOperationsByTopicFromCurrentWeekApiWorker
//     *
//     * @DI\InjectParams({
//     *     "collectOperationsByTopicFromCurrentWeekApiWorker" = @DI\Inject("muchacuba.info_sms.subscription.collect_operations_by_topic_from_current_week_api_worker"),
//     * })
//     */
//    function __construct(
//        CollectOperationsByTopicFromCurrentWeekApiWorker $collectOperationsByTopicFromCurrentWeekApiWorker
//    )
//    {
//        $this->collectOperationsByTopicFromCurrentWeekApiWorker = $collectOperationsByTopicFromCurrentWeekApiWorker;
//    }
//
//    /**
//     * @param string $topic
//     *
//     * @Req\Route("/info-sms/subscription/{topic}/collect-operations-by-topic-from-current-week")
//     * @Req\Method({"GET"})
//     *
//     * @return JsonResponse
//     */
//    public function collectAction($topic)
//    {
//        return new JsonResponse(
//            $this->collectOperationsByTopicFromCurrentWeekApiWorker->collect($topic)
//        );
//    }
}
