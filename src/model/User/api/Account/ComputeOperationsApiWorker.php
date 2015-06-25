<?php

namespace Muchacuba\User\Account;

use Muchacuba\User\Account\Operation\ConnectToStorageInternalWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ComputeOperationsApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * @return int[]
     */
    public function computeFromCurrentYear()
    {
        $response = $this->connectToStorageInternalWorker->connect()
            ->aggregate(
                [
                    ['$match' => [
                        'year' => date('Y')
                    ]],
                    ['$group' => [
                        '_id' => [
                            'month' => '$month'
                        ],
                        'count' => [
                            '$sum' => 1
                        ]
                    ]]
                ]
            );

        $stats = [];
        for ($i = 0; $i <= 11; $i++) {
            $found = false;

            foreach ($response['result'] as $item) {
                if ($i == (int) $item['_id']['month']) {
                    $stats[] = $item['count'];

                    $found = true;

                    break;
                }
            }

            if (!$found) {
                $stats[] = 0;
            }
        }

        return $stats;
    }
}
