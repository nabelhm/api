<?php

namespace Muchacuba\RechargeCard\Profile;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class IncreaseDebtTestWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.profile.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Increases the debt to the profile with given uniqueness, the given
     * amount.
     *
     * @param string $uniqueness
     * @param int    $amount
     */
    public function increase($uniqueness, $amount)
    {
        $this->connectToStorageInternalWorker->connect()->update(
            [
                'uniqueness' => $uniqueness
            ],
            ['$inc' => [
                'debt' => $amount,
            ]]
        );
    }
}