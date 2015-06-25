<?php

namespace Muchacuba\User;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;

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
     * @When the system has the following user accounts:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingUserAccounts(PyStringNode $body)
    {
        /** @var CreateAccountTestWorker $createAccountTestWorker */
        $createAccountTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.user.create_account_test_worker');

        $items = json_decode($body->getRaw(), true);

        foreach ($items as $item) {
            $createAccountTestWorker->create(
                $item['id'],
                $item['username'],
                $item['password'],
                $item['roles']
            );
        }
    }
}
