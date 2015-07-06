<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Profile\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\RechargeCard\Profile\NonExistentUniquenessInternalException;

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
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.profile.connect_to_storage_internal_worker")
     * })
     */
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * @param string   $uniqueness
     * @param string[] $cards
     *
     * @throws NonExistentUniquenessInternalException
     */
    public function assign($uniqueness, $cards)
    {
        $profile = $this->connectToStorageInternalWorker->connect()
            ->findOne([
                'uniqueness' => $uniqueness
            ]);

        if (!$profile) {
            throw new NonExistentUniquenessInternalException($uniqueness);
        }

        foreach ($cards as $i => $card) {
            $this->connectToStorageInternalWorker->connect()->update(
                [
                    'uniqueness' => $uniqueness
                ],
                [
                    '$set' => [
                        'cards' => array_merge(
                            $profile['cards'],
                            $cards
                        )
                    ]
                ]
            );
        }
    }
}