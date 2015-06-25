<?php

namespace Muchacuba\Credit;

use Muchacuba\Credit\Profile\ConnectToStorageInternalWorker;
use Muchacuba\Credit\Profile\ExistentUniquenessSharedException;
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
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.credit.profile.connect_to_storage_internal_worker"),
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates a profile.
     *
     * @param string $uniqueness
     * @param int    $balance
     *
     * @throws ExistentUniquenessSharedException
     * @throws \MongoCursorException
     */
    public function create($uniqueness, $balance)
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