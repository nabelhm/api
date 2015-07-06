<?php

namespace Muchacuba\RechargeCard;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateCategoryApiWorker
{
    /**
     * @var CreateCategoryInternalWorker
     */
    private $createCategoryInternalWorker;

    /**
     * @param CreateCategoryInternalWorker $createCategoryInternalWorker
     *
     * @Di\InjectParams({
     *     "createCategoryInternalWorker" = @Di\Inject("muchacuba.recharge_card.create_category_internal_worker")
     * })
     */
    public function __construct(CreateCategoryInternalWorker $createCategoryInternalWorker)
    {
        $this->createCategoryInternalWorker = $createCategoryInternalWorker;
    }

    /**
     * Creates a category.
     *
     * @param string $name
     * @param string $utility
     */
    public function create($name, $utility)
    {
        $this->createCategoryInternalWorker->create(
            uniqid(),
            $name,
            (int) $utility
        );
    }
}
