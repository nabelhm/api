<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Topic\NonExistentIdApiException;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Topic\NonExistentIdInternalException;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickTopicApiWorker
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
    public function __construct(PickTopicInternalWorker $pickTopicInternalWorker)
    {
        $this->pickTopicInternalWorker = $pickTopicInternalWorker;
    }

    /**
     * Picks the topic with given id.
     *
     * @param string $id
     *
     * @return array A topic as an array with the following keys:
     *               id, title, description and average.
     *
     * @throws NonExistentIdApiException
     */
    public function pick($id)
    {
        try {
            return $this->pickTopicInternalWorker->pick($id);
        } catch (NonExistentIdInternalException $e) {
            throw new NonExistentIdApiException();
        }
    }
}
