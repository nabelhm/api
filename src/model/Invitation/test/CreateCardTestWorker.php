<?php

namespace Muchacuba\Invitation;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\Invitation\Card\ConnectToStorageInternalWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class CreateCardTestWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.invitation.card.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates a card.
     *
     * @param string $code
     * @param string $role
     */
    public function create($code, $role)
    {
        $this->connectToStorageInternalWorker->connect()->insert(array(
            'code' => $code,
            'role' => $role,
            'consumed' => false
        ));
    }
}