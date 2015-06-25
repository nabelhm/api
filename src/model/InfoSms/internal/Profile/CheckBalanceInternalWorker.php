<?php

namespace Muchacuba\InfoSms\Profile;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CheckBalanceInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.profile.connect_to_storage_internal_worker"),
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Checks if the profile with given uniqueness has given amount.
     *
     * @param string $uniqueness
     * @param int    $amount
     *
     * @return bool True if balance is greater or equal than given amount, false
     *              otherwise.
     */
    public function check($uniqueness, $amount)
    {
        $item = $this->connectToStorageInternalWorker->connect()->findOne(
            [
                'uniqueness' => $uniqueness
            ], [
                'balance'
            ]
        );

        return $item['balance'] >= $amount;
    }
}
