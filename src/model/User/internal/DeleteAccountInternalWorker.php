<?php

namespace Muchacuba\User;

use Cubalider\Unique\DeleteUniquenessSharedWorker;
use Cubalider\Unique\NonExistentIdSharedException;
use Muchacuba\Authentication\DeleteProfileSharedWorker as DeleteAuthenticationProfileSharedWorker;
use Muchacuba\Internet\DeleteProfileSharedWorker as DeleteInternetProfileSharedWorker;
use Muchacuba\Mobile\Profile\NonExistentUniquenessSharedException as NonExistentMobileProfileSharedException;
use Muchacuba\Internet\Profile\NonExistentUniquenessSharedException as NonExistentInternetProfileSharedException;
use Muchacuba\Privilege\DeleteProfileSharedWorker as DeletePrivilegeProfileSharedWorker;
use Muchacuba\Mobile\DeleteProfileSharedWorker as DeleteMobileProfileSharedWorker;
use Muchacuba\Credit\DeleteProfileSharedWorker as DeleteCreditProfileSharedWorker;
use Muchacuba\InfoSms\DeleteProfileSharedWorker as DeleteInfoSmsProfileSharedWorker;
use Muchacuba\RechargeCard\DeleteProfileSharedWorker as DeleteRechargeCardProfileSharedWorker;
use Muchacuba\User\Account\LogOperationInternalWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DeleteAccountInternalWorker
{
    /**
     * @var DeleteUniquenessSharedWorker
     */
    private $deleteUniquenessSharedWorker;

    /**
     * @var DeleteMobileProfileSharedWorker
     */
    private $deleteMobileProfileSharedWorker;

    /**
     * @var DeleteInternetProfileSharedWorker
     */
    private $deleteInternetProfileSharedWorker;

    /**
     * @var DeletePrivilegeProfileSharedWorker
     */
    private $deletePrivilegeProfileSharedWorker;
    
    /**
     * @var DeleteAuthenticationProfileSharedWorker
     */
    private $deleteAuthenticationProfileSharedWorker;

    /**
     * @var DeleteCreditProfileSharedWorker
     */
    private $deleteCreditProfileSharedWorker;

    /**
     * @var DeleteRechargeCardProfileSharedWorker
     */
    private $deleteRechargeCardProfileSharedWorker;

    /*
     * @var DeleteInfoSmsProfileSharedWorker
     */
    private $deleteInfoSmsProfileSharedWorker;

    /**
     * @var LogOperationInternalWorker
     */
    private $logOperationInternalWorker;

    /**
     * @param DeleteUniquenessSharedWorker            $deleteUniquenessSharedWorker
     * @param DeleteMobileProfileSharedWorker         $deleteMobileProfileSharedWorker
     * @param DeleteInternetProfileSharedWorker       $deleteInternetProfileSharedWorker
     * @param DeletePrivilegeProfileSharedWorker      $deletePrivilegeProfileSharedWorker
     * @param DeleteAuthenticationProfileSharedWorker $deleteAuthenticationProfileSharedWorker
     * @param DeleteCreditProfileSharedWorker         $deleteCreditProfileSharedWorker
     * @param DeleteRechargeCardProfileSharedWorker   $deleteRechargeCardProfileSharedWorker
     * @param DeleteInfoSmsProfileSharedWorker        $deleteInfoSmsProfileSharedWorker
     * @param LogOperationInternalWorker              $logOperationInternalWorker
     */
    function __construct(
        DeleteUniquenessSharedWorker $deleteUniquenessSharedWorker,
        DeleteMobileProfileSharedWorker $deleteMobileProfileSharedWorker,
        DeleteInternetProfileSharedWorker $deleteInternetProfileSharedWorker,
        DeletePrivilegeProfileSharedWorker $deletePrivilegeProfileSharedWorker,
        DeleteAuthenticationProfileSharedWorker $deleteAuthenticationProfileSharedWorker,
        DeleteCreditProfileSharedWorker $deleteCreditProfileSharedWorker,
        DeleteRechargeCardProfileSharedWorker $deleteRechargeCardProfileSharedWorker,
        DeleteInfoSmsProfileSharedWorker $deleteInfoSmsProfileSharedWorker,
        LogOperationInternalWorker $logOperationInternalWorker
    )
    {
        $this->deleteUniquenessSharedWorker = $deleteUniquenessSharedWorker;
        $this->deleteMobileProfileSharedWorker = $deleteMobileProfileSharedWorker;
        $this->deleteInternetProfileSharedWorker = $deleteInternetProfileSharedWorker;
        $this->deletePrivilegeProfileSharedWorker = $deletePrivilegeProfileSharedWorker;
        $this->deleteAuthenticationProfileSharedWorker = $deleteAuthenticationProfileSharedWorker;
        $this->deleteCreditProfileSharedWorker = $deleteCreditProfileSharedWorker;
        $this->deleteRechargeCardProfileSharedWorker = $deleteRechargeCardProfileSharedWorker;
        $this->deleteInfoSmsProfileSharedWorker = $deleteInfoSmsProfileSharedWorker;
        $this->logOperationInternalWorker = $logOperationInternalWorker;
    }

    /**
     * Deletes a user account.
     *
     * @param string $uniqueness
     *
     * @throws NonExistentIdSharedException
     */
    public function delete($uniqueness)
    {
        try {
            $this->deleteUniquenessSharedWorker->delete($uniqueness);
        } catch (NonExistentIdSharedException $e) {
            throw $e;
        }

        try {
            $this->deleteMobileProfileSharedWorker->delete($uniqueness);
        } catch (NonExistentMobileProfileSharedException $e) {
        }
        
        try {
            $this->deleteInternetProfileSharedWorker->delete($uniqueness);
        } catch (NonExistentInternetProfileSharedException $e) {
        }
        
        $this->deletePrivilegeProfileSharedWorker->delete($uniqueness);

        $this->deleteAuthenticationProfileSharedWorker->delete($uniqueness);

        $this->deleteCreditProfileSharedWorker->delete($uniqueness);

        $this->deleteRechargeCardProfileSharedWorker->delete($uniqueness);

        $this->deleteInfoSmsProfileSharedWorker->delete($uniqueness);

        $this->logOperationInternalWorker->logDeletion($uniqueness);
    }
}