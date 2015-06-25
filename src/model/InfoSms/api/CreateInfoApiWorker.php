<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Info\BlankBodyApiException;
use Muchacuba\InfoSms\Info\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Info\NoTopicsApiException;
use Muchacuba\InfoSms\Info\NonExistentTopicApiException;
use Muchacuba\InfoSms\Topic\NonExistentIdApiException;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateInfoApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var PickTopicApiWorker
     */
    private $pickTopicApiWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     * @param PickTopicApiWorker             $pickTopicApiWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.info.connect_to_storage_internal_worker"),
     *     "pickTopicApiWorker"             = @Di\Inject("muchacuba.info_sms.pick_topic_api_worker"),
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        PickTopicApiWorker $pickTopicApiWorker
    ) {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->pickTopicApiWorker = $pickTopicApiWorker;
    }

    /**
     * Creates an info.
     *
     * @param string   $body
     * @param string[] $topics
     *
     * @throws BlankBodyApiException
     * @throws NoTopicsApiException
     * @throws NonExistentTopicApiException
     */
    public function create($body, $topics)
    {
        if ($body === '') {
            throw new BlankBodyApiException();
        }

        if (count($topics) == 0) {
            throw new NoTopicsApiException();
        }

        foreach ($topics as $topic) {
            try {
                $this->pickTopicApiWorker->pick($topic);
            } catch (NonExistentIdApiException $e) {
                throw new NonExistentTopicApiException();
            }
        }

        $this->connectToStorageInternalWorker->connect()->insert(
            array(
                'id' => uniqid(),
                'body' => $body,
                'topics' => $topics,
                'created' => time()
            )
        );
    }
}
