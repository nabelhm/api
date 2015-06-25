<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Profile\ConnectToStorageInternalWorker;
use Muchacuba\RechargeCard\Profile\NonExistentUniquenessApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickProfileApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var CollectAssignedCardsInternalWorker
     */
    private $collectAssignedCardsInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker     $connectToStorageInternalWorker
     * @param CollectAssignedCardsInternalWorker $collectAssignedCardsInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker"     = @Di\Inject("muchacuba.recharge_card.profile.connect_to_storage_internal_worker"),
     *     "collectAssignedCardsInternalWorker" = @Di\Inject("muchacuba.recharge_card.collect_assigned_cards_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        CollectAssignedCardsInternalWorker $collectAssignedCardsInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->collectAssignedCardsInternalWorker = $collectAssignedCardsInternalWorker;
    }

    /**
     * Picks the profile with given uniqueness.
     *
     * @param string $uniqueness
     *
     * @return array An array with the following keys:
     *               uniqueness and cards.
     *
     * @throws NonExistentUniquenessApiException
     */
    public function pick($uniqueness)
    {
        $profile = $this->connectToStorageInternalWorker->connect()
            ->findOne([
                'uniqueness' => $uniqueness
            ]);

        if (!$profile) {
            throw new NonExistentUniquenessApiException();
        }

        $cards = $this->collectAssignedCardsInternalWorker->collect($uniqueness);

        return [
            'uniqueness' => $profile['uniqueness'],
            'debt' => $profile['debt'],
            'cards' => $cards
        ];
    }

}