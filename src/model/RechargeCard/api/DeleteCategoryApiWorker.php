<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Category\ConnectToStorageInternalWorker;
use Muchacuba\RechargeCard\Category\NonExistentIdApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DeleteCategoryApiWorker
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
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Deletes the category with given id.
     *
     * @param string $id
     *
     * @throws NonExistentIdApiException
     */
    public function delete($id)
    {
        $result = $this->connectToStorageInternalWorker->connect()->remove(array(
            'id' => $id
        ));

        if ($result['n'] == 0) {
            throw new NonExistentIdApiException();
        }
    }
}