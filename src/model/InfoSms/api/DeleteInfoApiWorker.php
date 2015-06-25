<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Info\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Info\NonExistentIdApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DeleteInfoApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.info.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Deletes the info with given id.
     *
     * @param string $id
     *
     * @throws NonExistentIdApiException
     */
    public function delete($id)
    {
        $result = $this->connectToStorageInternalWorker->connect()->remove([
            'id' => $id,
        ]);

        if ($result['n'] == 0) {
            throw new NonExistentIdApiException();
        }
    }
}
