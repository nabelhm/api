<?php

namespace Muchacuba;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class WebApiDebugContext implements SnippetAcceptingContext
{
    /**
     * @var WebApiContext
     */
    private $webApiContext;

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function gatherOutcomeContext(BeforeScenarioScope $scope)
    {
        /** @var \Behat\Behat\Context\Environment\InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();

        $this->webApiContext = $environment->getContext('Muchacuba\WebApiContext');
    }

    /**
     * @AfterStep
     *
     * @param AfterStepScope $scope
     */
    public function printResponse(AfterStepScope $scope)
    {
        if (!$scope->getTestResult()->isPassed()
            && $this->webApiContext->getRequest()
            && $this->webApiContext->getResponse()
        ) {
            $this->webApiContext->printResponse();
        }
    }
}
