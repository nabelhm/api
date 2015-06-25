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
class IncreaseBalanceSharedWorker
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
     * Increases the balance to the profile with given uniqueness, the given
     * amount. It also logs the operation using given description.
     *
     * @param string $uniqueness
     * @param int    $amount
     * @param string $description
     *
     * @throws NonExistentUniquenessSharedException
     */
    public function increase($uniqueness, $amount, $description)
    {
        $result = $this->connectToStorageInternalWorker->connect()->update(
            [
                'uniqueness' => $uniqueness
            ],
            ['$inc' => [
                'balance' => $amount,
            ]]
        );

        if ($result['n'] == 0) {
            throw new NonExistentUniquenessSharedException($uniqueness);
        }

        $this->logOperationInternalWorker->log(
            $uniqueness,
            $amount,
            LogOperationInternalWorker::IMPACT_POSITIVE,
            $description
        );
    }
}