<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectTopicsApiWorker;
use Muchacuba\InfoSms\UpdateTopicApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UpdateTopicController
{
    /**
     * @var UpdateTopicApiWorker
     */
    private $updateTopicApiWorker;

    /**
     * @var CollectTopicsApiWorker
     */
    private $collectTopicsApiWorker;

    /**
     * @param UpdateTopicApiWorker   $updateTopicApiWorker
     * @param CollectTopicsApiWorker $collectTopicsApiWorker
     *
     * @DI\InjectParams({
     *     "updateTopicApiWorker"   = @DI\Inject("muchacuba.info_sms.update_topic_api_worker"),
     *     "collectTopicsApiWorker" = @DI\Inject("muchacuba.info_sms.collect_topics_api_worker"),
     * })
     */
    function __construct(
        UpdateTopicApiWorker $updateTopicApiWorker,
        CollectTopicsApiWorker $collectTopicsApiWorker
    )
    {
        $this->updateTopicApiWorker = $updateTopicApiWorker;
        $this->collectTopicsApiWorker = $collectTopicsApiWorker;
    }

    /**
     * @Req\Route("/info-sms/update-topic/{id}")
     * @Req\Method({"POST"})
     *
     * @param string  $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateAction($id, Request $request)
    {
        $data = $request->request->all();

        foreach (array('title','description', 'average', 'active', 'order') as $key) {
            Assertion::keyExists($data, $key);
        }

        $this->updateTopicApiWorker->update(
            $id,
            $data['title'],
            $data['description'],
            $data['average'],
            $data['active'],
            $data['order']
        );

        return new JsonResponse(
            $this->collectTopicsApiWorker->collect()
        );
    }
}