<?php

namespace Muchacuba\RechargeCard\Profile\Debt;

use Muchacuba\RechargeCard\Profile\Debt\Operation\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class LogOperationInternalWorker
{
    const IMPACT_POSITIVE = '+';
    const IMPACT_NEGATIVE = '-';

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
     * Logs a debt operation.
     *
     * @param string $uniqueness
     * @param int    $amount
     * @param string $impact
     * @param string $description
     */
    public function log($uniqueness, $amount, $impact, $description)
    {
        $this->connectToStorageInternalWorker->connect()->insert(
            array(
                'uniqueness' => $uniqueness,
                'amount' => $amount,
                'impact' => $impact,
                'description' => $description,
                'created' => time()
            )
        );
    }
}
