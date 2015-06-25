<?php

namespace Muchacuba\Invitation;

use Muchacuba\Invitation\AssignedCard\ConnectToStorageInternalWorker as ConnectToAssignedCardStorageInternalWorker;
use Muchacuba\Invitation\Card;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickProfileApiWorker
{
    /**
     * @var ConnectToAssignedCardStorageInternalWorker
     */
    private $connectToAssignedCardStorageInternalWorker;

    /**
     * @var PickCardInternalWorker
     */
    private $pickCardInternalWorker;

    /**
     * @param ConnectToAssignedCardStorageInternalWorker $connectToAssignedCardStorageInternalWorker
     * @param PickCardInternalWorker                     $pickCardInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToAssignedCardStorageInternalWorker" = @Di\Inject("muchacuba.invitation.assigned_card.connect_to_storage_internal_worker"),
     *     "pickCardInternalWorker"                     = @Di\Inject("muchacuba.invitation.pick_card_internal_worker")
     * })
     */
    public function __construct(
        ConnectToAssignedCardStorageInternalWorker $connectToAssignedCardStorageInternalWorker,
        PickCardInternalWorker $pickCardInternalWorker
    )
    {
        $this->connectToAssignedCardStorageInternalWorker = $connectToAssignedCardStorageInternalWorker;
        $this->pickCardInternalWorker = $pickCardInternalWorker;
    }

    /**
     * Picks the profile with given uniqueness.
     *
     * @param string $uniqueness
     *
     * @return Profile
     */
    public function pick($uniqueness)
    {
        $assignedCards = $this->connectToAssignedCardStorageInternalWorker->connect()
            ->find([
                'uniqueness' => $uniqueness
            ])
            ->fields([
                '_id' => 0 // Exclude
            ]);

        $cards = [];
        foreach ($assignedCards as $assignedCard) {
            $cards[] = $this->pickCardInternalWorker->pick($assignedCard['card']);
        }

        return [
            'uniqueness' => $uniqueness,
            'cards' => $cards
        ];
    }

}