<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Package\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Package\NonExistentIdApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DeletePackageApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.package.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Deletes the package with given id.
     *
     * @param string $id
     *
     * @throws NonExistentIdApiException
     */
    public function delete($id)
    {
        $result = $this->connectToStorageInternalWorker->connect()->remove(array(
            'id' => $id,
        ));

        if ($result['n'] == 0) {
            throw new NonExistentIdApiException();
        }
    }
}
