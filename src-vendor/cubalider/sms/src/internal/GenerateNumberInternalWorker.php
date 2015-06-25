<?php

namespace Cubalider\Sms;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class GenerateNumberInternalWorker
{
    /**
     * Generates a random number, valid to use as the "from" when sending a sms.
     * i.e.: +4418273645, +4597582735.
     *
     * @param bool $prefix
     *
     * @return string
     */
    public function generate($prefix = true)
    {
        $number = $prefix ? '+' : '';
        $number .= '44';

        for ($i = 1; $i <= 10; $i++) {
            $number .= rand(1, 9);
        }

        return $number;
    }
}