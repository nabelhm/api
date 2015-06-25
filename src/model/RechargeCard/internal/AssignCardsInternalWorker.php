<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\AssignedCard\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class AssignCardsInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.assigned_card.connect_to_storage_internal_worker")
     * })
     */
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * @param string   $uniqueness
     * @param string[] $cards
     */
    public function assign($uniqueness, $cards)
    {
        foreach ($cards as $i => $card) {
            $this->connectToStorageInternalWorker->connect()->insert([
                'uniqueness' => $uniqueness,
                'card' => $card
            ]);
        }
    }
}