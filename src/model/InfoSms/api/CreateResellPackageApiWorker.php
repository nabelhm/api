<?php

namespace Muchacuba\InfoSms;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateResellPackageApiWorker
{
    /**
     * @var CreateResellPackageInternalWorker
     */
    private $createResellPackageInternalWorker;

    /**
     * @param CreateResellPackageInternalWorker $createResellPackageInternalWorker
     *
     * @Di\InjectParams({
     *     "createResellPackageInternalWorker" = @Di\Inject("muchacuba.info_sms.create_resell_package_internal_worker"),
     * })
     */
    public function __construct(CreateResellPackageInternalWorker $createResellPackageInternalWorker)
    {
        $this->createResellPackageInternalWorker = $createResellPackageInternalWorker;
    }

    /**
     * Creates a resell package.
     *
     * @param int    $amount
     * @param int    $price
     * @param string $description
     */
    public function create($amount, $price, $description)
    {
        $this->createResellPackageInternalWorker->create(
            uniqid(),
            $amount,
            $price,
            $description
        );
    }
}
