<?php

namespace Muchacuba\Invitation;

use Muchacuba\Invitation\AssignedCard\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
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
     *     "connectToStorageInternalWorker"  = @Di\Inject("muchacuba.invitation.assigned_card.connect_to_storage_internal_worker")
     * })
     */
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Assigns given cards to the given uniqueness.
     *
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