<?php

namespace Muchacuba\Credit\Profile\Balance;

use Muchacuba\Credit\Profile\Balance\Operation\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class LogOperationInternalWorker
{
    CONST IMPACT_POSITIVE = '+';
    CONST IMPACT_NEGATIVE = '-';

    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.credit.profile.balance.operation.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Logs an operation.
     *
     * @param string $uniqueness
     * @param int    $amount
     * @param string $impact
     * @param string $description
     */
    public function log($uniqueness, $amount, $impact, $description)
    {
        $this->connectToStorageInternalWorker->connect()->insert([
            'uniqueness' => $uniqueness,
            'amount' => $amount,
            'impact' => $impact,
            'description' => $description,
            'created' => time()
        ]);
    }
}
