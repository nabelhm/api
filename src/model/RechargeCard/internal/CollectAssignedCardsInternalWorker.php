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
class CollectAssignedCardsInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var PickCardInternalWorker
     */
    private $pickCardInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     * @param PickCardInternalWorker         $pickCardInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.assigned_card.connect_to_storage_internal_worker"),
     *     "pickCardInternalWorker"         = @Di\Inject("muchacuba.recharge_card.pick_card_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        PickCardInternalWorker $pickCardInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->pickCardInternalWorker = $pickCardInternalWorker;
    }

    /**
     * Collects the cards assigned to the given uniqueness.
     *
     * @param string $uniqueness
     *
     * @return array An array of cards with the following keys:
     *               code, category and consumed.
     */
    public function collect($uniqueness)
    {
        $assignedCards = $this->connectToStorageInternalWorker->connect()
            ->find([
                'uniqueness' => $uniqueness
            ]);

        $cards = [];
        foreach ($assignedCards as $i => $assignedCard) {
            $cards[] = $this->pickCardInternalWorker->pick($assignedCard['card']);
        }

        return $cards;
    }

}