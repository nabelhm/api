<?php

namespace Muchacuba\Invitation;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;
use PHPUnit_Framework_Assert as Assert;

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
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
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
                $item['role']
            );
        }
    }
}