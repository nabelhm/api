<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Card\ConnectToStorageInternalWorker;
use Muchacuba\RechargeCard\Card\NonExistentCodeInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickCardInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.card.connect_to_storage_internal_worker")
     * })
     */
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Picks the card with given code.
     *
     * @param string $code
     *
     * @return array An array with the following keys:
     *               code, category and consumed.
     *
     * @throws NonExistentCodeInternalException
     */
    public function pick($code)
    {
        $card = $this->connectToStorageInternalWorker->connect()
            ->findOne(
                [
                    'code' => $code
                ],
                [
                    '_id' => 0 // Exclude
                ]
            );

        if (!$card) {
            throw new NonExistentCodeInternalException($code);
        }

        return $card;
    }
}
