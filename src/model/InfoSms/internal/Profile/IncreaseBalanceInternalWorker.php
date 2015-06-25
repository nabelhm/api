<?php

namespace Muchacuba\InfoSms\Profile;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class IncreaseBalanceInternalWorker
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
     * Increases to the profile with given uniqueness, the given amount of
     * messages.
     *
     * @param string $uniqueness
     * @param int    $amount
     *
     * @throws NonExistentUniquenessInternalException
     */
    public function increase($uniqueness, $amount)
    {
        $result = $this->connectToStorageInternalWorker->connect()->update(
            array('uniqueness' => $uniqueness),
            array('$inc' => array(
                'balance' => $amount,
            ))
        );

        if ($result['n'] == 0) {
            throw new NonExistentUniquenessInternalException($uniqueness);
        }
    }
}
