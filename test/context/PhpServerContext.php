<?php

namespace Muchacuba;

use Behat\Behat\Context\Context;
use Symfony\Component\Process\Process;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class PhpServerContext implements Context
{
    /**
     * @var Process
     */
    private static $phpServer;

    /**
     * @BeforeSuite
     */
    public static function startPhpServer()
    {
        self::$phpServer = new Process('php -S localhost:8000 web/app_test.php');
        self::$phpServer->start();
    }

    /**
     * @AfterSuite
     */
    public static function stopPhpServer()
    {
        self::$phpServer->stop();
    }
}