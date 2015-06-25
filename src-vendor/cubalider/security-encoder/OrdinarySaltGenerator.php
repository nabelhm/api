<?php

namespace Cubalider\Security;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class OrdinarySaltGenerator implements SaltGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
}
