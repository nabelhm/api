<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Subscription\BlankAliasApiException;
use Muchacuba\InfoSms\Subscription\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Subscription\NonExistentMobileAndUniquenessApiException;
use Muchacuba\InfoSms\Subscription\NoTopicsApiException;
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
     * @var PickTopicApiWorker
     */
    private $pickTopicApiWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     * @param PickTopicApiWorker             $pickTopicApiWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.connect_to_storage_internal_worker"),
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

        if (count($topics) == 0) {
            throw new NoTopicsApiException();
        }

        foreach ($topics as $topic) {
            try {
                $this->pickTopicApiWorker->pick($topic);
            } catch (NonExistentIdApiException $e) {
                throw $e;
            }
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
