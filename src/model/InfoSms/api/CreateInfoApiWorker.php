<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Info\BlankBodyApiException;
use Muchacuba\InfoSms\Info\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\NoTopicsApiException;
use Muchacuba\InfoSms\NonExistentTopicApiException;
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
        $this->validateTopicsInternalWorker = $validateTopicsInternalWorker;
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

        try {
            $this->validateTopicsInternalWorker->validate($topics);
        } catch (NoTopicsInternalException $e) {
            throw new NoTopicsApiException();
        } catch (NonExistentTopicInternalException $e) {
            throw new NonExistentTopicApiException();
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
