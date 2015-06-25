<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectTopicsApiWorker;
use Muchacuba\InfoSms\DeleteTopicApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DeleteTopicController
{
    /**
     * @var DeleteTopicApiWorker
     */
    private $deleteTopicApiWorker;

    /**
     * @var CollectTopicsApiWorker
     */
    private $collectTopicsApiWorker;
    /**
     * @param DeleteTopicApiWorker   $deleteTopicApiWorker
     * @param CollectTopicsApiWorker $collectTopicsApiWorker
     *
     * @DI\InjectParams({
     *     "deleteTopicApiWorker"   = @DI\Inject("muchacuba.info_sms.delete_topic_api_worker"),
     *     "collectTopicsApiWorker" = @DI\Inject("muchacuba.info_sms.collect_topics_api_worker"),
     * })
     */
    function __construct(
        DeleteTopicApiWorker $deleteTopicApiWorker,
        CollectTopicsApiWorker $collectTopicsApiWorker
    )
    {
        $this->deleteTopicApiWorker = $deleteTopicApiWorker;
        $this->collectTopicsApiWorker = $collectTopicsApiWorker;
    }

    /**
     * @Req\Route("/info-sms/delete-topic/{id}")
     * @Req\Method({"POST"})
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        $this->deleteTopicApiWorker->delete($id);

        return new JsonResponse(
            $this->collectTopicsApiWorker->collect()
        );
    }
}
