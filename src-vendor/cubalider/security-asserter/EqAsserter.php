<?php

namespace Cubalider\Security;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface EqAsserter
{
    /**
     * Asserts whether given source is equal to given target.
     *
     * @param string $source
     * @param string $target
     *
     * @return bool true if strings are equals, false otherwise
     */
    public function assert($source, $target);
}
