<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Info\BlankBodyApiException;
use Muchacuba\InfoSms\Info\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Info\NonExistentIdApiException;
use Muchacuba\InfoSms\NonExistentTopicApiException;
use Muchacuba\InfoSms\NoTopicsApiException;
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
     * @var ValidateTopicsInternalWorker
     */
    private $validateTopicsInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     * @param ValidateTopicsInternalWorker   $validateTopicsInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.info.connect_to_storage_internal_worker"),
     *     "validateTopicsInternalWorker"   = @Di\Inject("muchacuba.info_sms.validate_topics_internal_worker")
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        ValidateTopicsInternalWorker $validateTopicsInternalWorker
    ) {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->validateTopicsInternalWorker =$validateTopicsInternalWorker;
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

        try {
            $this->validateTopicsInternalWorker->validate($topics);
        } catch (NoTopicsInternalException $e) {
            throw new NoTopicsApiException();
        } catch (NonExistentTopicInternalException $e) {
            throw new NonExistentTopicApiException();
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
