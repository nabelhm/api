<?php

namespace Cubalider\Security;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CurlyBracketMerger implements Merger
{
    /**
     * {@inheritdoc}
     */
    public function merge($subject, $salt)
    {
        if (empty($salt)) {
            return $subject;
        }

        if (false !== strrpos($salt, '{') || false !== strrpos($salt, '}')) {
            throw new \DomainException('Cannot use { or } in salt.');
        }

        return sprintf("%s{%s}", $subject, $salt);
    }
}
