<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\ResellPackage\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateResellPackageInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.resell_package.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates a resell package.
     *
     * @param string $id
     * @param int    $amount
     * @param int    $price
     * @param string $description
     */
    public function create($id, $amount, $price, $description)
    {
        $this->connectToStorageInternalWorker->connect()->insert(array(
            'id' => $id,
            'amount' => (int) $amount,
            'price' => (int) $price,
            'description' => $description
        ));
    }
}
