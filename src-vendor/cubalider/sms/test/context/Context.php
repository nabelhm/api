<?php


namespace Cubalider\Sms;

use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Coduo\PHPMatcher\Factory\SimpleFactory;
use Symfony\Component\HttpKernel\KernelInterface;
use PHPUnit_Framework_Assert as Assert;
use Muchacuba\Context as RootContext;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 */
class Context implements SnippetAcceptingContext, KernelAwareContext
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    private $kernel;

    /**
     * @var RootContext
     */
    private $rootContext;

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function gatherRootContext(BeforeScenarioScope $scope)
    {
        /** @var InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();

        $this->rootContext = $environment->getContext('Muchacuba\Context');
    }

    /**
     * @Then the system should have the following sms messages:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingMessages(PyStringNode $body)
    {
        /** @var CollectMessagesTestWorker $collectMessagesTestWorker */
        $collectMessagesTestWorker = $this->kernel
            ->getContainer()
            ->get('cubalider.sms.collect_messages_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectMessagesTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Cubalider\Sms\Message');
    }
}
