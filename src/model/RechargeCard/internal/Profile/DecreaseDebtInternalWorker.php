<?php

namespace Muchacuba\RechargeCard\Profile;

use Muchacuba\RechargeCard\Profile\Debt\LogOperationInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DecreaseDebtInternalWorker
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
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.profile.connect_to_storage_internal_worker"),
     *     "logOperationInternalWorker"     = @Di\Inject("muchacuba.recharge_card.profile.debt.log_operation_internal_worker")
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
     * Decreases the debt to the profile with given uniqueness, the given amount.
     *
     * @param string $uniqueness
     * @param int    $amount
     * @param string $description
     *
     * @throws NonExistentUniquenessInternalException
     * @throws LowerDebtInternalException
     */
    public function decrease($uniqueness, $amount, $description)
    {
        $item = $this->connectToStorageInternalWorker->connect()->findOne(
            [
                'uniqueness' => $uniqueness
            ], [
                'debt'
            ]
        );

        if (!$item) {
            throw new NonExistentUniquenessInternalException($uniqueness);
        }

        if ($amount > $item['debt']) {
            throw new LowerDebtInternalException();
        }

        $this->connectToStorageInternalWorker->connect()->update(
            [
                'uniqueness' => $uniqueness
            ],
            ['$inc' => [
                'debt' => -1 * $amount,
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