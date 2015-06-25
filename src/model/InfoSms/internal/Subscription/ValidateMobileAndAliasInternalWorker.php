<?php

namespace Muchacuba\InfoSms\Subscription;

use JMS\DiExtraBundle\Annotation as Di;
use Cubalider\Phone\NumberFixer as PhoneNumberFixer;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ValidateMobileAndAliasInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.subscription.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Validates given mobile and alias.
     *
     * @param string $mobile
     * @param string $alias
     *
     * @throws InvalidMobileInternalException
     * @throws BlankAliasInternalException
     * @throws ExistentMobileInternalException
     */
    public function validate($mobile, $alias)
    {
        try {
            PhoneNumberFixer::fix($mobile);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidMobileInternalException();
        }

        if ($alias === '') {
            throw new BlankAliasInternalException();
        }

        $subscription = $this->connectToStorageInternalWorker->connect()->findOne([
            'mobile' => $mobile
        ]);

        if ($subscription) {
            throw new ExistentMobileInternalException();
        }
    }
}
