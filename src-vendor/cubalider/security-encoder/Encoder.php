<?php

namespace Cubalider\Security;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Encoder
{
    /**
     * Encodes a string.
     *
     * @param string $subject The subject to encode
     * @param string $salt    The salt
     *
     * @return string The encoded string
     *
     * @throws \InvalidArgumentException if subject or salt are invalid
     */
    public function encode($subject, $salt);
}
