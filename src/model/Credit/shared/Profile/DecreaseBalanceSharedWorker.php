<?php

namespace Muchacuba\Credit\Profile;

use Muchacuba\Credit\Profile\Balance\LogOperationInternalWorker;
use Muchacuba\InfoSms\Profile\NonExistentUniquenessSharedException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DecreaseBalanceSharedWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var LogOperationInternalWorker
     */
    private $logOperationInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     * @param LogOperationInternalWorker     $logOperationInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.credit.profile.connect_to_storage_internal_worker"),
     *     "logOperationInternalWorker"     = @Di\Inject("muchacuba.credit.profile.balance.log_operation_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        LogOperationInternalWorker $logOperationInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->logOperationInternalWorker = $logOperationInternalWorker;
    }

    /**
     * Decreases the balance to the profile with given uniqueness, the given
     * amount. It also logs the operation using given description.
     *
     * @param string $uniqueness
     * @param int    $amount
     * @param string $description
     *
     * @throws NonExistentUniquenessSharedException
     * @throws InsufficientBalanceSharedException
     */
    public function decrease($uniqueness, $amount, $description)
    {
        $item = $this->connectToStorageInternalWorker->connect()->findOne(
            [
                'uniqueness' => $uniqueness
            ], [
                'balance'
            ]
        );

        if (!$item) {
            throw new NonExistentUniquenessSharedException($uniqueness);
        }

        if ($amount > $item['balance']) {
            throw new InsufficientBalanceSharedException();
        }

        $this->connectToStorageInternalWorker->connect()->update(
            [
                'uniqueness' => $uniqueness
            ],
            ['$inc' => [
                'balance' => -1 * $amount,
            ]]
        );

        $this->logOperationInternalWorker->log(
            $uniqueness,
            $amount,
            LogOperationInternalWorker::IMPACT_NEGATIVE,
            $description
        );
    }
}