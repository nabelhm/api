<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Category\NonExistentIdInternalException as NonExistentCategoryInternalException;
use Muchacuba\RechargeCard\Package\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreatePackageInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var PickCategoryInternalWorker
     */
    private $pickCategoryInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     * @param PickCategoryInternalWorker     $pickCategoryInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.package.connect_to_storage_internal_worker"),
     *     "pickCategoryInternalWorker"     = @Di\Inject("muchacuba.recharge_card.pick_category_internal_worker")
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        PickCategoryInternalWorker $pickCategoryInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->pickCategoryInternalWorker = $pickCategoryInternalWorker;
    }

    /**
     * Creates a package.
     *
     * @param string $id
     * @param string $name
     * @param string $category
     * @param int    $amount
     * @param int    $price
     *
     * @throws NonExistentCategoryInternalException
     */
    public function create($id, $name, $category, $amount, $price)
    {
        try {
            $this->pickCategoryInternalWorker->pick($category);
        } catch (NonExistentCategoryInternalException $e) {
            throw $e;
        }

        $this->connectToStorageInternalWorker->connect()->insert(array(
            'id' => $id,
            'name' => $name,
            'category' => $category,
            'amount' => $amount,
            'price' => $price
        ));
    }
}
