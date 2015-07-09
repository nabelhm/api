<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Card\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateCardTestWorker
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
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates a card.
     *
     * @param string $code
     * @param string $category
     * @param string $consumed
     */
    public function create($code, $category, $consumed)
    {
        $this->connectToStorageInternalWorker->connect()->insert(array(
            'code' => $code,
            'category' => $category,
            'consumed' => $consumed
        ));
    }
}