<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\CollectTopicsApiWorker;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\NonExistentTopicInternalException;
use Muchacuba\InfoSms\NoTopicsInternalException;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ValidateTopicsInternalWorker
{
    /**
     * @var CollectTopicsApiWorker
     */
    private $collectTopicsWorker;

    /**
     * @param CollectTopicsApiWorker         $collectTopicsWorker
     *
     * @Di\InjectParams({
     *     "collectTopicsWorker"            = @Di\Inject("muchacuba.info_sms.collect_topics_api_worker"),
     * })
     */
    function __construct(
        CollectTopicsApiWorker $collectTopicsWorker
    )
    {
        $this->collectTopicsWorker = $collectTopicsWorker;
    }

    /**
     * Validates given topics.
     *
     * @throws NoTopicsInternalException
     * @throws NonExistentTopicInternalException
     */
    public function validate($topics)
    {
        if (count($topics) == 0) {
            throw new NoTopicsInternalException();
        }

        $existentTopics = $this->collectTopicsWorker->collect();
        $topicIds = [];

        foreach ($existentTopics as $i => $topic) {
            $topicIds[$i] = $topic['id'];
        }

        $diffTopic = array_diff($topics, $topicIds);

        if (!empty($diffTopic)){
            throw new NonExistentTopicInternalException();
        }
    }
}
