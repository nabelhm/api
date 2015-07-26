<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Subscription\BlankAliasApiException;
use Muchacuba\InfoSms\Subscription\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Subscription\NonExistentMobileAndUniquenessApiException;
use Muchacuba\InfoSms\NonExistentTopicApiException;
use Muchacuba\InfoSms\NoTopicsApiException;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Topic\NonExistentIdApiException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class UpdateSubscriptionApiWorker
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
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.connect_to_storage_internal_worker"),
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
     * Updates the subscription with given mobile and uniqueness.
     *
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string   $alias
     * @param string[] $topics
     * @param boolean  $active
     *
     * @throws BlankAliasApiException
     * @throws NoTopicsApiException
     * @throws NonExistentIdApiException
     * @throws NonExistentMobileAndUniquenessApiException
     */
    public function update($mobile, $uniqueness, $alias, $topics, $active)
    {
        if ($alias === '') {
            throw new BlankAliasApiException();
        }

        try {
            $this->validateTopicsInternalWorker->validate($topics);
        } catch (NoTopicsInternalException $e) {
            throw new NoTopicsApiException();
        } catch (NonExistentTopicInternalException $e) {
            throw new NonExistentTopicApiException();
        }

        $result = $this->connectToStorageInternalWorker->connect()->update(
            [
                'mobile' => $mobile,
                'uniqueness' => $uniqueness
            ],
            ['$set' => [
                'alias' => $alias,
                'topics' => $topics,
                'active' => $active,
                'order' => time()
            ]]
        );

        if ($result['n'] == 0) {
            throw new NonExistentMobileAndUniquenessApiException();
        }
    }
}
