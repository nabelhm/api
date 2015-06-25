<?php

namespace Cubalider;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Manuel Emilio Carpio <mectwork@gmail.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class CodeGenerator
{
    /**
     * Generates a code using given pattern and divider.
     *
     * @param string $pattern
     * @param string $divider
     *
     * @return string
     */
    public function generate($pattern = 'xxxx-xxxx-xxxx', $divider = '-')
    {
        $i = 0;
        $length = strlen($pattern);
        while ($i < $length) {
            $pattern = preg_replace('/x/', rand(0, 9), $pattern, 1);
            $i++;
        }
        $code = str_replace('-', $divider, $pattern);

        return $code;
    }
}