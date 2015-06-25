<?php

namespace Cubalider\Security;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PlainEncoder implements Encoder
{
    /**
     * @var Merger
     */
    private $merger;

    /**
     * @param Merger|null $merger
     */
    function __construct(Merger $merger = null)
    {
        $this->merger = $merger ?: new CurlyBracketMerger();
    }

    /**
     * {@inheritdoc}
     */
    public function encode($subject, $salt)
    {
        if (empty($salt)) {
            return $subject;
        }

        try {
            return $this->merger->merge($subject, $salt);
        } catch (\DomainException $e) {
            throw new \DomainException($e->getMessage());
        }
    }
}
