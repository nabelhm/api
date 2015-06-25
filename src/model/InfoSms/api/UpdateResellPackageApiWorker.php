<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\ResellPackage\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\ResellPackage\NonExistentIdApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class UpdateResellPackageApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.resell_package.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Updates the resell package with given id.
     *
     * @param string $id
     * @param int    $amount
     * @param int    $price
     * @param string $description
     *
     * @throws NonExistentIdApiException
     */
    public function update($id, $amount, $price, $description)
    {
        $result = $this->connectToStorageInternalWorker->connect()->update(
            array('id' => $id),
            array('$set' => array(
                'amount' => $amount,
                'price' => $price,
                'description' => $description
            ))
        );

        if ($result['n'] == 0) {
            throw new NonExistentIdApiException();
        }
    }
}
