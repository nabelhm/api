<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Profile\ConnectToStorageInternalWorker;
use Muchacuba\RechargeCard\Profile\ExistentUniquenessSharedException;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class CreateProfileSharedWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @DI\InjectParams({
     *     "connectToStorageInternalWorker" = @DI\Inject("muchacuba.recharge_card.profile.connect_to_storage_internal_worker"),
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates a profile.
     *
     * @param string   $uniqueness
     * @param int      $debt
     * @param string[] $cards
     *
     * @throws ExistentUniquenessSharedException
     * @throws \MongoCursorException
     */
    public function create($uniqueness, $debt, $cards)
    {
        try {
            $this->connectToStorageInternalWorker->connect()->insert([
                'uniqueness' => $uniqueness,
                'debt' => $debt,
                'cards' => $cards
            ]);
        } catch (\MongoCursorException $e) {
            if (11000 == $e->getCode()) {
                throw new ExistentUniquenessSharedException();
            }

            throw $e;
        }
    }
}