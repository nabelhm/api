<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Info\BlankBodyApiException;
use Muchacuba\InfoSms\Info\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Info\NonExistentIdApiException;
use Muchacuba\InfoSms\Info\NonExistentTopicApiException;
use Muchacuba\InfoSms\Info\NoTopicsApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class UpdateInfoApiWorker
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
     * Updates the info with given id.
     *
     * @param string   $id
     * @param string   $body
     * @param string[] $topics
     *
     * @throws BlankBodyApiException
     * @throws NoTopicsApiException
     * @throws NonExistentTopicApiException
     * @throws NonExistentIdApiException
     */
    public function update($id, $body, $topics)
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

        $result = $this->connectToStorageInternalWorker->connect()->update(
            array('id' => $id),
            array('$set' => array(
                'body' => $body,
                'topics' => $topics,
                'created' => time() //TODO: Test that the created field was updated, maybe using collect
            ))
        );

        if ($result['n'] == 0) {
            throw new NonExistentIdApiException();
        }
    }
}
