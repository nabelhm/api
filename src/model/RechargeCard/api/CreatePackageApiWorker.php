<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Package\NonExistentCategoryApiException;
use Muchacuba\RechargeCard\Category\NonExistentIdInternalException as NonExistentCategoryInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreatePackageApiWorker
{
    /**
     * @var CreatePackageInternalWorker
     */
    private $createPackageInternalWorker;

    /**
     * @param CreatePackageInternalWorker $createPackageInternalWorker
     *
     * @Di\InjectParams({
     *     "createPackageInternalWorker" = @Di\Inject("muchacuba.recharge_card.create_package_internal_worker")
     * })
     */
    public function __construct(CreatePackageInternalWorker $createPackageInternalWorker)
    {
        $this->createPackageInternalWorker = $createPackageInternalWorker;
    }

    /**
     * Creates a package.
     *
     * @param string $name
     * @param string $category
     * @param int    $amount
     * @param int    $price
     *
     * @throws NonExistentCategoryApiException
     */
    public function create($name, $category, $amount, $price)
    {
        try {
            $this->createPackageInternalWorker->create(
                uniqid(),
                $name,
                $category,
                $amount,
                $price
            );
        } catch (NonExistentCategoryInternalException $e) {
            throw new NonExistentCategoryApiException();
        }
    }
}
