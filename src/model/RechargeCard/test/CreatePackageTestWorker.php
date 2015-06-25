<?php

namespace Muchacuba\RechargeCard;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreatePackageTestWorker
{
    /**
     * @var CreatePackageInternalWorker
     */
    private $createPackageInternalWorker;

    /**
     * @param CreatePackageInternalWorker $createPackageInternalWorker
     *
     * @Di\InjectParams({
     *     "createPackageInternalWorker" = @Di\Inject("muchacuba.recharge_card.create_package_internal_worker"),
     * })
     */
    public function __construct(CreatePackageInternalWorker $createPackageInternalWorker)
    {
        $this->createPackageInternalWorker = $createPackageInternalWorker;
    }

    /**
     * Creates a package.
     *
     * @param string $id
     * @param string $name
     * @param string $category
     * @param int    $amount
     * @param int    $price
     */
    public function create($id, $name, $category, $amount, $price)
    {
        $this->createPackageInternalWorker->create(
            $id,
            $name,
            $category,
            $amount,
            $price
        );
    }
}
