<?php

namespace Muchacuba\InfoSms;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateTopicTestWorker
{
    /**
     * @var CreateTopicInternalWorker
     */
    private $createTopicInternalWorker;

    /**
     * @param CreateTopicInternalWorker $createTopicInternalWorker
     *
     * @Di\InjectParams({
     *     "createTopicInternalWorker" = @Di\Inject("muchacuba.info_sms.create_topic_internal_worker")
     * })
     */
    public function __construct(CreateTopicInternalWorker $createTopicInternalWorker)
    {
        $this->createTopicInternalWorker = $createTopicInternalWorker;
    }

    /**
     * Creates a topic.
     *
     * @param string $id
     * @param string $title
     * @param string $description
     * @param string $average
     * @param int    $order
     */
    public function create($id, $title, $description, $average, $order)
    {
        $this->createTopicInternalWorker->create(
            $id,
            $title,
            $description,
            $average,
            $order
        );
    }
}
