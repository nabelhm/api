<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Profile\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class CreateProfileSharedWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.profile.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    ) {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates an profile.
     *
     * @param string   $uniqueness
     * @param int|null $balance
     *
     * @return string The id
     *
     * @throws ExistentUniquenessSharedException
     * @throws \MongoCursorException
     */
    public function create($uniqueness, $balance = 0)
    {
        try {
            $this->connectToStorageInternalWorker->connect()->insert([
                'uniqueness' => $uniqueness,
                'balance' => $balance,
            ]);
        } catch (\MongoCursorException $e) {
            if (11000 == $e->getCode()) {
                throw new ExistentUniquenessSharedException();
            }

            throw $e;
        }
    }
}
