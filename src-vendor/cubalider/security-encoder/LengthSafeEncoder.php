<?php

namespace Cubalider\Security;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class LengthSafeEncoder implements Encoder
{
    /**
     * @var Encoder
     */
    private $encoder;

    /**
     * @var int
     */
    private $length;

    /**
     * @param Encoder $encoder
     * @param int     $length
     */
    public function __construct(Encoder $encoder, $length = 4096)
    {
        $this->encoder = $encoder;
        $this->length = $length;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($subject, $salt)
    {
        if (strlen($subject) > $this->length) {
            throw new \DomainException('Subject is too long.');
        }

        return $this->encoder->encode($subject, $salt);
    }
}
