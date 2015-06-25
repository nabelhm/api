<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectTopicsApiWorker;
use Muchacuba\InfoSms\CreateTopicApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CreateTopicController
{
    /**
     * @var CreateTopicApiWorker
     */
    private $createTopicApiWorker;

    /**
     * @var CollectTopicsApiWorker
     */
    private $collectTopicsApiWorker;

    /**
     * @param CreateTopicApiWorker   $createTopicApiWorker
     * @param CollectTopicsApiWorker $collectTopicsApiWorker
     *
     * @DI\InjectParams({
     *     "createTopicApiWorker"   = @DI\Inject("muchacuba.info_sms.create_topic_api_worker"),
     *     "collectTopicsApiWorker" = @DI\Inject("muchacuba.info_sms.collect_topics_api_worker"),
     * })
     */
    function __construct(
        CreateTopicApiWorker $createTopicApiWorker,
        CollectTopicsApiWorker $collectTopicsApiWorker
    )
    {
        $this->createTopicApiWorker = $createTopicApiWorker;
        $this->collectTopicsApiWorker = $collectTopicsApiWorker;
    }

    /**
     * @Req\Route("/info-sms/create-topic")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();

        foreach (array('title','description', 'average', 'order') as $key) {
            Assertion::keyExists($data, $key);
        }

        $this->createTopicApiWorker->create(
            $data['title'],
            $data['description'],
            $data['average'],
            $data['order']
        );

        return new JsonResponse(
            $this->collectTopicsApiWorker->collect()
        );
    }
}