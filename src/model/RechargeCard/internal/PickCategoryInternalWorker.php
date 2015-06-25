<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Category\ConnectToStorageInternalWorker;
use Muchacuba\RechargeCard\Category\NonExistentIdInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickCategoryInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.category.connect_to_storage_internal_worker")
     * })
     */
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Picks the category with given id.
     *
     * @param string $id
     *
     * @return array An array with the following keys:
     *               id, name and utility
     *
     * @throws NonExistentIdInternalException
     */
    public function pick($id)
    {
        $category = $this->connectToStorageInternalWorker->connect()
            ->findOne(
                [
                    'id' => $id
                ],
                [
                    '_id' => 0 // Exclude
                ]
            );

        if (!$category) {
            throw new NonExistentIdInternalException($id);
        }

        return $category;
    }

}