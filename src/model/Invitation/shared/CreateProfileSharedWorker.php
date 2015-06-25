<?php

namespace Muchacuba\Invitation;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class CreateProfileSharedWorker
{
    /**
     * Creates a profile.
     *
     * @param string $uniqueness
     */
    public function create($uniqueness)
    {
    }
}