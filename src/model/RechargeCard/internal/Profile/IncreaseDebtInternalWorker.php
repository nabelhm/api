<?php

namespace Muchacuba\RechargeCard\Profile;

use Muchacuba\RechargeCard\Profile\Debt\LogOperationInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class IncreaseDebtInternalWorker
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
     * Increases the debt to the profile with given uniqueness, the given
     * amount. It also logs the operation using given description.
     *
     * @param string $uniqueness
     * @param int    $amount
     * @param string $description
     *
     * @throws NonExistentUniquenessInternalException
     */
    public function increase($uniqueness, $amount, $description)
    {
        $result = $this->connectToStorageInternalWorker->connect()->update(
            [
                'uniqueness' => $uniqueness
            ],
            ['$inc' => [
                'debt' => $amount,
            ]]
        );

        if ($result['n'] == 0) {
            throw new NonExistentUniquenessInternalException($uniqueness);
        }

        $this->logOperationInternalWorker->log(
            $uniqueness,
            $amount,
            LogOperationInternalWorker::IMPACT_POSITIVE,
            $description
        );
    }
}