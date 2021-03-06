<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ValidateTopicsApiWorker
{
    /**
     * @var ValidateTopicsInternalWorker
     */
    private $validateTopicsInternalWorker;

    /**
     * @param ValidateTopicsInternalWorker $validateTopicsInternalWorker
     *
     * @Di\InjectParams({
     *     "validateTopicsInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.validate_topics_internal_worker")
     * })
     */
    function __construct(
        ValidateTopicsInternalWorker $validateTopicsInternalWorker
    )
    {
        $this->validateTopicsInternalWorker = $validateTopicsInternalWorker;
    }

    /**
     * Validates given topics.
     *
     * @throws NoTopicsApiException
     * @throws NonExistentTopicApiException
     */
    public function validate($topics)
    {
        try {
            $this->validateTopicsInternalWorker->validate($topics);
        } catch (NoTopicsInternalException $e) {
            throw new NoTopicsApiException();
        } catch (NonExistentTopicInternalException $e) {
            throw new NonExistentTopicApiException();
        }
    }
}
