<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Package\ConnectToStorageInternalWorker;
use Muchacuba\RechargeCard\Package\NonExistentIdInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickPackageInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.package.connect_to_storage_internal_worker")
     * })
     */
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Picks the package with given id.
     *
     * @param string $id
     *
     * @return array An array with the following keys:
     *               id, name, category, amount and price
     *
     * @throws NonExistentIdInternalException
     */
    public function pick($id)
    {
        $package = $this->connectToStorageInternalWorker->connect()
            ->findOne(
                [
                    'id' => $id
                ],
                [
                    '_id' => 0 // Exclude
                ]
            );

        if (!$package) {
            throw new NonExistentIdInternalException($id);
        }

        return $package;
    }
}
