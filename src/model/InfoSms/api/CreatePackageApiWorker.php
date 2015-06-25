<?php

namespace Muchacuba\InfoSms;

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
     *     "createPackageInternalWorker" = @Di\Inject("muchacuba.info_sms.create_package_internal_worker"),
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
     * @param int    $amount
     * @param int    $price
     */
    public function create($name, $amount, $price)
    {
        $this->createPackageInternalWorker->create(
            uniqid(),
            $name,
            $amount,
            $price
        );
    }
}
