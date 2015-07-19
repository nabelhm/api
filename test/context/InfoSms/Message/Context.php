<?php


namespace Muchacuba\InfoSms\Message;

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
     * @Then the system should have the following info sms message links:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingLinks(PyStringNode $body)
    {
        $expected = (array) json_decode($body->getRaw(), true);

        /** @var CollectLinksTestWorker $collectLinksTestWorker */
        $collectLinksTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.message.collect_links_test_worker');

        $actual = iterator_to_array($collectLinksTestWorker->collect());

        (new SimpleFactory())->createMatcher()->match($actual, $expected)
            ?: (new \PHPUnit_Framework_Constraint_IsEqual($expected))->evaluate($actual);

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Message\Link');
    }

    /**
     * @Then the system should have the following info sms message stats:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingStats(PyStringNode $body)
    {
        $expected = (array) json_decode($body->getRaw(), true);

        /** @var CollectStatsTestWorker $collectStatsTestWorker */
        $collectStatsTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.message.collect_stats_test_worker');

        $actual = iterator_to_array($collectStatsTestWorker->collect());

        (new SimpleFactory())->createMatcher()->match($actual, $expected)
            ?: (new \PHPUnit_Framework_Constraint_IsEqual($expected))->evaluate($actual);

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Message\Stat');
    }
}
