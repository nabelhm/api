<?php

namespace Cubalider\Security;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ConstantTimeEqAsserter implements EqAsserter
{
    /**
     * {@inheritdoc}
     */
    public function assert($source, $target)
    {
        $sourceLen = strlen($source);
        $targetLen = strlen($target);

        // Extend target to avoid uninitialized string offsets
        $target .= $source;

        $equality = $sourceLen - $targetLen;
        for ($i = 0; $i < $sourceLen; $i++) {
            $equality |= ord($source[$i]) ^ ord($target[$i]);
        }

        return 0 === $equality;
    }
}
