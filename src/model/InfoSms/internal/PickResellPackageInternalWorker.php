<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\ResellPackage\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\ResellPackage\NonExistentIdInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickResellPackageInternalWorker
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
     * Picks the resell package with given id.
     *
     * @param string $id
     *
     * @return array A resell package as an array with the following keys:
     *               id, amount, and price.
     *
     * @throws NonExistentIdInternalException
     */
    public function pick($id)
    {
        $resellPackage = $this->connectToStorageInternalWorker->connect()
            ->findOne(
                [
                    'id' => $id
                ],
                [
                    '_id' => 0
                ]
            );

        if (!$resellPackage) {
            throw new NonExistentIdInternalException();
        }

        return $resellPackage;
    }
}
