<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\Operation\ConnectToStorageInternalWorker as ConnectToOperationStorageInternalWorker;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class LogOperationInternalWorker
{
    /**
     * @var ConnectToOperationStorageInternalWorker
     */
    private $connectToOperationStorageInternalWorker;

    /**
     * @param ConnectToOperationStorageInternalWorker $connectToOperationStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToOperationStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.operation.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToOperationStorageInternalWorker $connectToOperationStorageInternalWorker)
    {
        $this->connectToOperationStorageInternalWorker = $connectToOperationStorageInternalWorker;
    }

    /**
     * Logs a trial operation.
     *
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string[] $topics
     * @param int      $timestamp
     */
    public function logTrial($mobile, $uniqueness, $topics, $timestamp)
    {
        $this->connectToOperationStorageInternalWorker->connect()->insert([
            'type' => 0,
            'mobile' => $mobile,
            'uniqueness' => $uniqueness,
            'topics' => $topics,
            'timestamp' => new \MongoDate($timestamp)
        ]);
    }

    /**
     * Logs a create operation.
     *
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string[] $topics
     * @param int      $amount
     * @param int      $timestamp
     */
    public function logCreate($mobile, $uniqueness, $topics, $amount, $timestamp)
    {
        $this->connectToOperationStorageInternalWorker->connect()->insert([
            'type' => 1,
            'mobile' => $mobile,
            'uniqueness' => $uniqueness,
            'topics' => $topics,
            'amount' => $amount,
            'timestamp' => new \MongoDate($timestamp)
        ]);
    }

    /**
     * Logs a recharge operation.
     *
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string[] $topics
     * @param int      $trial
     * @param int      $balance
     * @param int      $amount
     * @param int      $timestamp
     */
    public function logRecharge($mobile, $uniqueness, $topics, $trial, $balance, $amount, $timestamp)
    {
        $this->connectToOperationStorageInternalWorker->connect()->insert([
            'type' => 2,
            'mobile' => $mobile,
            'uniqueness' => $uniqueness,
            'topics' => $topics,
            'trial' => $trial,
            'balance' => $balance,
            'amount' => $amount,
            'timestamp' => new \MongoDate($timestamp)
        ]);
    }

    /**
     * Logs a delete operation.
     *
     * @param string   $mobile
     * @param string   $uniqueness
     * @param string[] $topics
     * @param int      $trial
     * @param int      $balance
     * @param int      $timestamp
     */
    public function logDelete($mobile, $uniqueness, $topics, $trial, $balance, $timestamp)
    {
        $this->connectToOperationStorageInternalWorker->connect()->insert([
            'type' => 3,
            'mobile' => $mobile,
            'uniqueness' => $uniqueness,
            'topics' => $topics,
            'trial' => $trial,
            'balance' => $balance,
            'timestamp' => new \MongoDate($timestamp)
        ]);
    }
}
