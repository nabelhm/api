<?php

namespace Muchacuba\Invitation;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Profile implements \JsonSerializable
{
    /**
     * @var string
     */
    private $uniqueness;

    /**
     * @var Card[]
     */
    private $cards;

    /**
     * @param string $uniqueness
     * @param Card[] $cards
     */
    function __construct($uniqueness, $cards)
    {
        $this->uniqueness = $uniqueness;
        $this->cards = $cards;
    }

    /**
     * @return string
     */
    public function getUniqueness()
    {
        return $this->uniqueness;
    }

    /**
     * @return Card[]
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'uniqueness' => $this->uniqueness,
            'cards' => $this->cards
        ];
    }
}