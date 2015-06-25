<?php

namespace Cubalider\Security;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Merger
{
    /**
     * Merges given subject with given salt.
     *
     * @param string $subject
     * @param string $salt
     *
     * @throws \InvalidArgumentException if given salt is invalid
     *
     * @return string
     */
    public function merge($subject, $salt);
}
