<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Category\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class CreateCategoryInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.category.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates a category.
     *
     * @param string $id
     * @param string $name
     * @param int    $utility
     *
     * @throws \MongoCursorException
     */
    public function create($id, $name, $utility)
    {
        $this->connectToStorageInternalWorker->connect()->insert(array(
            'id' => $id,
            'name' => $name,
            'utility' => $utility
        ));
    }

}