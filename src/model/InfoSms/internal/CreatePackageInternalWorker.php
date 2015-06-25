<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Package\ConnectToStorageInternalWorker;
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
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.package.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates a package.
     *
     * @param string $id
     * @param string $name
     * @param int    $amount
     * @param int    $price
     */
    public function create($id, $name, $amount, $price)
    {
        $this->connectToStorageInternalWorker->connect()->insert(array(
            'id' => $id,
            'name' => $name,
            'amount' => $amount,
            'price' => $price
        ));
    }
}
