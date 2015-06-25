<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\PickTopicApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class PickTopicController
{
    /**
     * @var PickTopicApiWorker
     */
    private $pickTopicApiWorker;

    /**
     * @param PickTopicApiWorker $pickTopicApiWorker
     *
     * @DI\InjectParams({
     *     "pickTopicApiWorker" = @DI\Inject("muchacuba.info_sms.pick_topic_api_worker"),
     * })
     */
    function __construct(
        PickTopicApiWorker $pickTopicApiWorker
    )
    {
        $this->pickTopicApiWorker = $pickTopicApiWorker;
    }

    /**
     * @Req\Route("/info-sms/pick-topic/{topic}")
     * @Req\Method({"GET"})
     *
     * @param string $topic
     *
     * @return JsonResponse
     */
    public function pickAction($topic)
    {
        return new JsonResponse(
            $this->pickTopicApiWorker->pick($topic)
        );
    }
}