<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\Credit\Profile\IncreaseBalanceSharedWorker as IncreaseCreditBalanceSharedWorker;
use Muchacuba\RechargeCard\Card\AlreadyConsumedApiException;
use Muchacuba\RechargeCard\Card\NonExistentCodeApiException;
use Muchacuba\RechargeCard\Card\NonExistentCodeInternalException;
use Muchacuba\RechargeCard\Profile\NonExistentUniquenessApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ConsumeCardApiWorker
{
    /**
     * @var PickCardInternalWorker
     */
    private $pickCardInternalWorker;

    /**
     * @var PickProfileApiWorker
     */
    private $pickProfileApiWorker;

    /**
     * @var PickCategoryInternalWorker
     */
    private $pickCategoryInternalWorker;

    /**
     * @var PunchCardInternalWorker
     */
    private $punchCardInternalWorker;

    /**
     * @var IncreaseCreditBalanceSharedWorker
     */
    private $increaseCreditBalanceSharedWorker;

    /**
     * @param PickCardInternalWorker            $pickCardInternalWorker
     * @param PickProfileApiWorker              $pickProfileApiWorker
     * @param PickCategoryInternalWorker        $pickCategoryInternalWorker
     * @param PunchCardInternalWorker           $punchCardInternalWorker
     * @param IncreaseCreditBalanceSharedWorker $increaseCreditBalanceSharedWorker
     *
     * @Di\InjectParams({
     *     "pickCardInternalWorker"            = @Di\Inject("muchacuba.recharge_card.pick_card_internal_worker"),
     *     "pickProfileApiWorker"              = @Di\Inject("muchacuba.recharge_card.pick_profile_api_worker"),
     *     "pickCategoryInternalWorker"        = @Di\Inject("muchacuba.recharge_card.pick_category_internal_worker"),
     *     "punchCardInternalWorker"           = @Di\Inject("muchacuba.recharge_card.punch_card_internal_worker"),
     *     "increaseCreditBalanceSharedWorker" = @Di\Inject("muchacuba.credit.profile.increase_balance_shared_worker"),
     * })
     */
    function __construct(
        PickCardInternalWorker $pickCardInternalWorker,
        PickProfileApiWorker $pickProfileApiWorker,
        PickCategoryInternalWorker $pickCategoryInternalWorker,
        PunchCardInternalWorker $punchCardInternalWorker,
        IncreaseCreditBalanceSharedWorker $increaseCreditBalanceSharedWorker
    )
    {
        $this->pickCardInternalWorker = $pickCardInternalWorker;
        $this->pickProfileApiWorker = $pickProfileApiWorker;
        $this->pickCategoryInternalWorker = $pickCategoryInternalWorker;
        $this->punchCardInternalWorker = $punchCardInternalWorker;
        $this->increaseCreditBalanceSharedWorker = $increaseCreditBalanceSharedWorker;
    }

    /**
     * Consumes given card, increasing the credit balance of given uniqueness
     * and marking the card as consumed.
     *
     * @param string $uniqueness
     * @param string $card
     *
     * @throws NonExistentCodeApiException
     * @throws NonExistentUniquenessApiException
     * @throws AlreadyConsumedApiException
     */
    public function consume($uniqueness, $card)
    {
        // Check card code

        try {
            $card = $this->pickCardInternalWorker->pick($card);
        } catch (NonExistentCodeInternalException $e) {
            throw new NonExistentCodeApiException();
        }
        
        // Check uniqueness

        try {
            $this->pickProfileApiWorker->pick($uniqueness);
        } catch (NonExistentUniquenessApiException $e) {
            throw $e;
        }

        // Check card status

        if ($card['consumed']) {
            throw new AlreadyConsumedApiException();
        }

        // Get category

        $category = $this->pickCategoryInternalWorker->pick($card['category']);

        // Increase credit balance

        $this->increaseCreditBalanceSharedWorker->increase(
            $uniqueness,
            $category['utility'],
            sprintf("Recarga de saldo con tarjeta de recarga \"%s\"", $card['code'])
        );

        // Mark card as consumed

        $this->punchCardInternalWorker->punch($card['code']);
    }
}