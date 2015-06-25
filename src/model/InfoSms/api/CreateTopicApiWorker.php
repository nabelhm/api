<?php

namespace Muchacuba\InfoSms;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateTopicApiWorker
{
    /**
     * @var CreateTopicInternalWorker
     */
    private $createTopicInternalWorker;

    /**
     * @param CreateTopicInternalWorker $createTopicInternalWorker
     *
     * @Di\InjectParams({
     *     "createTopicInternalWorker" = @Di\Inject("muchacuba.info_sms.create_topic_internal_worker"),
     * })
     */
    public function __construct(CreateTopicInternalWorker $createTopicInternalWorker)
    {
        $this->createTopicInternalWorker = $createTopicInternalWorker;
    }

    /**
     * Creates a topic.
     *
     * @param string $title
     * @param string $description
     * @param int    $average
     * @param int    $order
     */
    public function create($title, $description, $average, $order)
    {
        $this->createTopicInternalWorker->create(
            uniqid(),
            $title,
            $description,
            $average,
            $order
        );
    }
}
