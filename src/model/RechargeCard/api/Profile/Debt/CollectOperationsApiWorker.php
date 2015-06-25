<?php

namespace Muchacuba\RechargeCard\Profile\Debt;

use Muchacuba\RechargeCard\Profile\Debt\Operation\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectOperationsApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.profile.debt.operation.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Collect operations for given uniqueness.
     *
     * @param string $uniqueness
     *
     * @return \Iterator An array of operations with the following keys:
     *                   uniqueness, amount, impact, description and created
     */
    public function collect($uniqueness)
    {
        return $this->connectToStorageInternalWorker->connect()
            ->find([
                'uniqueness' => $uniqueness
            ])
            ->fields([
                '_id' => 0 // Exclude
            ])
            ->sort([
                'created' => -1 // Descending
            ]);
    }
}
