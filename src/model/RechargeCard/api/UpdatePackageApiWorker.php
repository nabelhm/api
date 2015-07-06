<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Package\ConnectToStorageInternalWorker;
use Muchacuba\RechargeCard\Category\NonExistentIdApiException as NonExistentCategoryApiException;
use Muchacuba\RechargeCard\Package\NonExistentIdApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class UpdatePackageApiWorker
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
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        PickCategoryInternalWorker            $pickCategoryInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->pickCategoryInternalWorker = $pickCategoryInternalWorker;
    }

    /**
     * Updates the package with given code.
     *
     * @param string $id
     * @param string $name
     * @param string $category
     * @param int    $amount
     * @param int    $price
     *
     * @throws NonExistentCategoryApiException
     * @throws NonExistentIdApiException
     */
    public function update($id, $name, $category, $amount, $price)
    {
        try {
            $this->pickCategoryInternalWorker->pick($category);
        } catch (NonExistentCategoryApiException $e) {
            throw $e;
        }

        $result = $this->connectToStorageInternalWorker->connect()->update(
            array('id' => $id),
            array('$set' => array(
                'name' => $name,
                'category' => $category,
                'amount' => (int) $amount,
                'price' => (int) $price
            ))
        );

        if ($result['n'] == 0) {
            throw new NonExistentIdApiException();
        }
    }
}