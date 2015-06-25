<?php

namespace Cubalider\Phone;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NumberFixer
{
    /**
     * @param string $number
     *
     * @return int
     *
     * @throws \InvalidArgumentException if the number is invalid
     */
    public static function fix($number)
    {
        $number = str_replace(
            ['+', '-', ' ', '(', ')'],
            '',
            $number
        );

        if (strlen($number) == 8) {
            $number = sprintf("53%s", $number);
        }

        if (!ctype_digit((string) $number) || strlen($number) != 10) {
            throw new \InvalidArgumentException();
        }

        return sprintf("+%s", $number);
    }
}
