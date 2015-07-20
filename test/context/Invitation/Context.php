<?php

namespace Muchacuba\Invitation;

use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
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
     * @When the invitation card profile :uniqueness has assigned :amount cards of :role
     */
    public function theInvitationCardProfileHasAssignedCardsOf($uniqueness, $amount, $role)
    {
        /** @var GiveCardsTestWorker $giveCardsTestWorker */
        $giveCardsTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.invitation.give_cards_test_worker');

        $giveCardsTestWorker ->give($uniqueness, $role, (int) $amount);
    }

    /**
     * @Given the system has the following invitation cards:
     *
     * @param PyStringNode $body
     */
    public function theUserHasTheFollowingInvitationCards(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var CreateCardTestWorker $createCardTestWorker */
        $createCardTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.invitation.create_card_test_worker');

        foreach ($items as $item) {
            $createCardTestWorker->create(
                $item['code'],
                $item['role'],
                $item['consumed']
            );
        }
    }

    /**
     * @Given the system should have the following invitation cards:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingCards(PyStringNode $body)
    {
        /** @var CollectCardsTestWorker $collectCardsTestWorker */
        $collectCardsTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.invitation.collect_cards_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectCardsTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\Invitation\Card');
    }

    /**
     * @Given the system should have the following invitation assigned cards:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingInvitationAssignedCards(PyStringNode $body)
    {
        /** @var CollectAssignedCardsTestWorker $collectCardsTestWorker */
        $collectCardsTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.invitation.collect_assigned_cards_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectCardsTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\Invitation\AssignedCard');
    }
}