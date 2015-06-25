<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Profile\Debt\LogOperationInternalWorker;
use Muchacuba\RechargeCard\Profile\DecreaseDebtInternalWorker;
use Muchacuba\RechargeCard\Profile\GreaterThanRealDebtApiException;
use Muchacuba\RechargeCard\Profile\InvalidAmountApiException;
use Muchacuba\RechargeCard\Profile\LowerDebtInternalException;
use Muchacuba\RechargeCard\Profile\NonExistentUniquenessApiException;
use Muchacuba\RechargeCard\Profile\NonExistentUniquenessInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class LiquidateDebtApiWorker
{
    /**
     * @var DecreaseDebtInternalWorker
     */
    private $decreaseDebtInternalWorker;

    /**
     * @var LogOperationInternalWorker
     */
    private $logOperationInternalWorker;

    /**
     * @param DecreaseDebtInternalWorker $decreaseDebtInternalWorker
     * @param LogOperationInternalWorker $logOperationInternalWorker
     *
     * @Di\InjectParams({
     *     "decreaseDebtInternalWorker" = @Di\Inject("muchacuba.recharge_card.profile.decrease_debt_internal_worker"),
     *     "logOperationInternalWorker" = @Di\Inject("muchacuba.recharge_card.profile.debt.log_operation_internal_worker")
     * })
     */
    function __construct(
        DecreaseDebtInternalWorker $decreaseDebtInternalWorker,
        LogOperationInternalWorker $logOperationInternalWorker
    )
    {
        $this->decreaseDebtInternalWorker = $decreaseDebtInternalWorker;
        $this->logOperationInternalWorker = $logOperationInternalWorker;
    }

    /**
     * Lends to the given username, the given amount of money.
     *
     * @param string $uniqueness
     * @param int    $amount
     *
     * @throws InvalidAmountApiException
     * @throws NonExistentUniquenessApiException
     * @throws GreaterThanRealDebtApiException
     */
    public function liquidate($uniqueness, $amount)
    {
        if (!ctype_digit((string) $amount)) {
            throw new InvalidAmountApiException();
        }

        try {
            $this->decreaseDebtInternalWorker->decrease(
                $uniqueness,
                $amount,
                'Liquidación'
            );
        } catch (NonExistentUniquenessInternalException $e) {
            throw new NonExistentUniquenessApiException();
        } catch (LowerDebtInternalException $e) {
            throw new GreaterThanRealDebtApiException();
        }
    }
}
