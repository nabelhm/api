<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\InfoSms\Package\NonExistentIdInternalException;
use Muchacuba\RechargeCard\Package\NonExistentIdApiException;
use Muchacuba\RechargeCard\Profile\IncreaseDebtInternalWorker;
use Muchacuba\RechargeCard\Profile\NonExistentUniquenessApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class LendCardsApiWorker
{
    /**
     * @var PickProfileApiWorker
     */
    private $pickProfileApiWorker;

    /**
     * @var PickPackageInternalWorker
     */
    private $pickPackageInternalWorker;

    /**
     * @var CreateCardsInternalWorker
     */
    private $createCardsInternalWorker;

    /**
     * @var AssignCardsInternalWorker
     */
    private $assignCardsInternalWorker;

    /**
     * @var IncreaseDebtInternalWorker
     */
    private $increaseDebtInternalWorker;

    /**
     * @var PickCategoryInternalWorker
     */
    private $pickCategoryInternalWorker;

    /**
     * @param PickProfileApiWorker       $pickProfileApiWorker
     * @param PickPackageInternalWorker  $pickPackageInternalWorker
     * @param CreateCardsInternalWorker  $createCardsInternalWorker
     * @param AssignCardsInternalWorker  $assignCardsInternalWorker
     * @param IncreaseDebtInternalWorker $increaseDebtInternalWorker
     * @param PickCategoryInternalWorker $pickCategoryInternalWorker
     *
     * @Di\InjectParams({
     *     "pickProfileApiWorker"       = @Di\Inject("muchacuba.recharge_card.pick_profile_api_worker"),
     *     "pickPackageInternalWorker"  = @Di\Inject("muchacuba.recharge_card.pick_package_internal_worker"),
     *     "createCardsInternalWorker"  = @Di\Inject("muchacuba.recharge_card.create_cards_internal_worker"),
     *     "assignCardsInternalWorker"  = @Di\Inject("muchacuba.recharge_card.assign_cards_internal_worker"),
     *     "increaseDebtInternalWorker" = @Di\Inject("muchacuba.recharge_card.profile.increase_debt_internal_worker"),
     *     "pickCategoryInternalWorker" = @Di\Inject("muchacuba.recharge_card.pick_category_internal_worker")
     * })
     */
    function __construct(
        PickProfileApiWorker $pickProfileApiWorker,
        PickPackageInternalWorker $pickPackageInternalWorker,
        CreateCardsInternalWorker $createCardsInternalWorker,
        AssignCardsInternalWorker $assignCardsInternalWorker,
        IncreaseDebtInternalWorker $increaseDebtInternalWorker,
        PickCategoryInternalWorker $pickCategoryInternalWorker
    )
    {
        $this->pickProfileApiWorker = $pickProfileApiWorker;
        $this->pickPackageInternalWorker = $pickPackageInternalWorker;
        $this->createCardsInternalWorker = $createCardsInternalWorker;
        $this->assignCardsInternalWorker = $assignCardsInternalWorker;
        $this->increaseDebtInternalWorker = $increaseDebtInternalWorker;
        $this->pickCategoryInternalWorker = $pickCategoryInternalWorker;
    }

    /**
     * @param string $uniqueness
     * @param string $package
     *
     * @throws NonExistentUniquenessApiException
     * @throws NonExistentIdApiException
     */
    public function lend($uniqueness, $package)
    {
        // Verify uniqueness

        try {
            $this->pickProfileApiWorker->pick($uniqueness);
        } catch (NonExistentUniquenessApiException $e) {
            throw $e;
        }

        // Get package

        try {
            $package = $this->pickPackageInternalWorker->pick($package);
        } catch (NonExistentIdInternalException $e) {
            throw new NonExistentIdApiException();
        }

        // Generate cards

        $cards = $this->createCardsInternalWorker->create(
            $package['category'],
            $package['amount']
        );

        // Assign cards

        $this->assignCardsInternalWorker->assign(
            $uniqueness,
            $cards
        );

        // Increase debt

        $category = $this->pickCategoryInternalWorker->pick(
            $package['category']
        );

        $this->increaseDebtInternalWorker->increase(
            $uniqueness, 
            $package['price'],
            sprintf(
                "Préstamo de %s tarjetas de categoría \"%s\"",
                $package['amount'],
                $category['name']
            )
        );
    }
}