<?php

namespace Muchacuba\InfoSms\Profile;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class SetBalanceTestWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.profile.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Sets to the profile with given uniqueness, the given amount of
     * sms to the balance.
     *
     * @param string $uniqueness
     * @param int    $amount
     *
     * @throws NonExistentUniquenessInternalException
     */
    public function set($uniqueness, $amount)
    {
        $result = $this->connectToStorageInternalWorker->connect()->update(
            array('uniqueness' => $uniqueness),
            array('$set' => array(
                'balance' => $amount
            ))
        );

        if ($result['n'] == 0) {
            throw new NonExistentUniquenessInternalException($uniqueness);
        }
    }
}
