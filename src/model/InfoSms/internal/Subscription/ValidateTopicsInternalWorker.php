<?php

namespace Muchacuba\InfoSms\Subscription;

use Muchacuba\InfoSms\PickTopicInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Topic\NonExistentIdInternalException;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ValidateTopicsInternalWorker
{
    /**
     * @var PickTopicInternalWorker
     */
    private $pickTopicInternalWorker;

    /**
     * @param PickTopicInternalWorker $pickTopicInternalWorker
     *
     * @Di\InjectParams({
     *     "pickTopicInternalWorker" = @Di\Inject("muchacuba.info_sms.pick_topic_internal_worker"),
     * })
     */
    function __construct(
        PickTopicInternalWorker $pickTopicInternalWorker
    )
    {
        $this->pickTopicInternalWorker = $pickTopicInternalWorker;
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

        foreach ($topics as $topic) {
            try {
                $this->pickTopicInternalWorker->pick($topic);
            } catch (NonExistentIdInternalException $e) {
                throw new NonExistentTopicInternalException();
            }
        }
    }
}
