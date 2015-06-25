<?php

namespace Cubalider\Security;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class HashEncoder implements Encoder
{
    /**
     * @var string
     */
    private $algorithm;

    /**
     * @var int
     */
    private $iterations;

    /**
     * @var bool
     */
    private $useBase64;

    /**
     * @var Merger
     */
    private $merger;

    /**
     * @param string|null $algorithm
     * @param int|null    $iterations
     * @param bool|null   $useBase64
     * @param Merger|null $merger
     */
    function __construct($algorithm = 'sha512', $iterations = 5000, $useBase64 = true, Merger $merger = null)
    {
        $this->algorithm = $algorithm;
        $this->iterations = $iterations;
        $this->useBase64 = $useBase64;
        $this->merger = $merger ?: new CurlyBracketMerger();
    }

    /**
     * {@inheritdoc}
     */
    public function encode($subject, $salt)
    {
        if (!in_array($this->algorithm, hash_algos(), true)) {
            throw new \DomainException(sprintf('The algorithm "%s" is not supported.', $this->algorithm));
        }

        try {
            $salted = $this->merger->merge($subject, $salt);
        } catch (\DomainException $e) {
            throw new \DomainException($e->getMessage());
        }

        $digest = hash($this->algorithm, $salted, true);
        for ($i = 1; $i < $this->iterations; $i++) {
            $digest = hash($this->algorithm, sprintf("%s%s", $digest, $salted), true);
        }

        return $this->useBase64 ? base64_encode($digest) : bin2hex($digest);
    }
}
