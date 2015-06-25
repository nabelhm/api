<?php

namespace Muchacuba\InfoSms;

use Muchacuba\Credit\Profile\DecreaseBalanceSharedWorker as DecreaseCreditBalanceSharedWorker;
use Muchacuba\Credit\Profile\CheckBalanceSharedWorker as CheckCreditBalanceSharedWorker;
use Muchacuba\InfoSms\Package\NonExistentIdApiException as NonExistentPackageApiException;
use Muchacuba\InfoSms\Profile\IncreaseBalanceInternalWorker;
use Muchacuba\InfoSms\Profile\InsufficientBalanceApiException;
use Muchacuba\InfoSms\Profile\NonExistentUniquenessApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class BuyPackageApiWorker
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
     * @var IncreaseBalanceInternalWorker
     */
    private $increaseBalanceInternalWorker;

    /**
     * @var CheckCreditBalanceSharedWorker
     */
    private $checkCreditBalanceSharedWorker;

    /**
     * @var DecreaseCreditBalanceSharedWorker
     */
    private $decreaseCreditBalanceSharedWorker;

    /**
     * @param PickProfileApiWorker              $pickProfileApiWorker
     * @param PickPackageInternalWorker         $pickPackageInternalWorker
     * @param IncreaseBalanceInternalWorker     $increaseBalanceInternalWorker
     * @param CheckCreditBalanceSharedWorker    $checkCreditBalanceSharedWorker
     * @param DecreaseCreditBalanceSharedWorker $decreaseCreditBalanceSharedWorker
     *
     * @Di\InjectParams({
     *     "pickProfileApiWorker"              = @Di\Inject("muchacuba.info_sms.pick_profile_api_worker"),
     *     "pickPackageInternalWorker"         = @Di\Inject("muchacuba.info_sms.pick_package_internal_worker"),
     *     "increaseBalanceInternalWorker"     = @Di\Inject("muchacuba.info_sms.profile.increase_balance_internal_worker"),
     *     "checkCreditBalanceSharedWorker"    = @Di\Inject("muchacuba.credit.profile.check_balance_shared_worker"),
     *     "decreaseCreditBalanceSharedWorker" = @Di\Inject("muchacuba.credit.profile.decrease_balance_shared_worker"),
     * })
     */
    public function __construct(
        PickProfileApiWorker $pickProfileApiWorker,
        PickPackageInternalWorker $pickPackageInternalWorker,
        IncreaseBalanceInternalWorker $increaseBalanceInternalWorker,
        CheckCreditBalanceSharedWorker $checkCreditBalanceSharedWorker,
        DecreaseCreditBalanceSharedWorker $decreaseCreditBalanceSharedWorker
    ) {
        $this->pickProfileApiWorker = $pickProfileApiWorker;
        $this->pickPackageInternalWorker = $pickPackageInternalWorker;
        $this->increaseBalanceInternalWorker = $increaseBalanceInternalWorker;
        $this->checkCreditBalanceSharedWorker = $checkCreditBalanceSharedWorker;
        $this->decreaseCreditBalanceSharedWorker = $decreaseCreditBalanceSharedWorker;
    }

    /**
     * @param string $uniqueness
     * @param string $package
     *
     * @throws NonExistentUniquenessApiException
     * @throws NonExistentPackageApiException
     * @throws InsufficientBalanceApiException
     */
    public function buy($uniqueness, $package)
    {
        try {
            $this->pickProfileApiWorker->pick($uniqueness);
        } catch (NonExistentUniquenessApiException $e) {
            throw $e;
        }

        // Get package info

        try {
            $package = $this->pickPackageInternalWorker->pick($package);
        } catch (NonExistentPackageApiException $e) {
            throw $e;
        }

        // Check if there is enough balance in credit profile

        if (!$this->checkCreditBalanceSharedWorker->check(
            $uniqueness,
            $package['price']
        )) {
            throw new InsufficientBalanceApiException();
        }

        // Increase amount of sms in profile

        $this->increaseBalanceInternalWorker->increase(
            $uniqueness,
            $package['amount']
        );

        // Decrease balance in credit profile

        $this->decreaseCreditBalanceSharedWorker->decrease(
            $uniqueness,
            $package['price'],
            sprintf("Compra de paquete de noticias por sms \"%s\"", $package['name'])
        );
    }
}
