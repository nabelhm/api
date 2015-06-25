<?php

namespace Muchacuba\RechargeCard;

use Cubalider\CodeGenerator;
use Muchacuba\RechargeCard\Card\ConnectToStorageInternalWorker;
use Muchacuba\RechargeCard\Category\NonExistentIdApiException as NonExistentCategoryException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Manuel Emilio Carpio <mectwork@gmail.com>
 *
 * @Di\Service()
 */
class CreateCardsInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    /**
     * @var PickCategoryInternalWorker
     */
    private $pickCategoryInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     * @param CodeGenerator                  $codeGenerator
     * @param PickCategoryInternalWorker     $pickCategoryInternalWorker
     * 
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.card.connect_to_storage_internal_worker"),
     *     "codeGenerator"                  = @Di\Inject("cubalider.code_generator"),
     *     "pickCategoryInternalWorker"     = @Di\Inject("muchacuba.recharge_card.pick_category_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        CodeGenerator $codeGenerator,
        PickCategoryInternalWorker $pickCategoryInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->codeGenerator = $codeGenerator;
        $this->pickCategoryInternalWorker = $pickCategoryInternalWorker;
    }

    /**
     * Creates cards.
     *
     * @param string  $category
     * @param integer $amount
     *
     * @return string[] the already created card codes
     *
     * @throws NonExistentCategoryException
     */
    public function create($category, $amount)
    {
        try {
            $this->pickCategoryInternalWorker->pick($category);
        } catch (NonExistentCategoryException $e) {
            throw $e;
        }

        $codes = [];
        for ($i = 1; $i <= $amount; $i++) {
            $code = $this->generateCode();

            $this->connectToStorageInternalWorker->connect()->insert(array(
                'code' => $code,
                'category' => $category,
                'consumed' => false
            ));

            $codes[] = $code;
        }

        return $codes;
    }

    /**
     * Generates a code, verifying that no card use it.
     *
     * @return string
     */
    private function generateCode()
    {
        $code = $this->codeGenerator->generate();

        while (
            $this->connectToStorageInternalWorker->connect()->findOne(array(
                'code' => $code
            ))
        ) {
            $code = $this->codeGenerator->generate();
        }

        return $code;
    }
}