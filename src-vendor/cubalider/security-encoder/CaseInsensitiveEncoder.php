<?php

namespace Cubalider\Security;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CaseInsensitiveEncoder implements Encoder
{
    /**
     * @var Encoder
     */
    private $encoder;

    /**
     * @param Encoder $encoder
     */
    function __construct(Encoder $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($subject, $salt)
    {
        return $this->encoder->encode(strtolower($subject), strtolower($salt));
    }
}
