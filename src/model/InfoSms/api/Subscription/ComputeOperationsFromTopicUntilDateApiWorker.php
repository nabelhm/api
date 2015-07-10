<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\Subscription\Operation\ConnectToStorageInternalWorker as ConnectToOperationStorageInternalWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ComputeOperationsFromTopicUntilDateApiWorker
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
    public function __construct(ConnectToOperationStorageInternalWorker $connectToOperationStorageInternalWorker)
    {
        $this->connectToOperationStorageInternalWorker = $connectToOperationStorageInternalWorker;
    }

    /**
     * @param string  $topic
     * @param string  $until
     *
     * @return int[]
     *
     * @throws InvalidGroupApiException
     */
    public function compute($topic, $until)
    {
        $response = $this->connectToOperationStorageInternalWorker->connect()
            ->aggregate(
                [
                    ['$match' => [
                        'topics' => $topic,
                        'timestamp' => [
                            '$lt' => new \MongoDate($until),
                        ]
                    ]],
                    ['$group' => [
                        '_id' => [
                            'type' => '$type'
                        ],
                        'total' => [
                            '$sum' => 1
                        ]
                    ]],
                    ['$sort' => ['_id' => 1]]
                ]
            );

        $stats = [];
        foreach ($response['result'] as $item) {
            $stats[] = [
                'total' => $item['total'],
                'type' => $item['_id']['type']
            ];
        }

        return $stats;
    }
}
