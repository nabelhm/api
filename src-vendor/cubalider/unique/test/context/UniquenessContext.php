<?php

namespace Cubalider\Unique;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Cubalider\ConnectToStorageWorker as ConnectToUniquenessStorageWorker;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UniquenessContext implements SnippetAcceptingContext, KernelAwareContext
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
     * @When there are the following uniquenesses:
     *
     * @param PyStringNode $body
     */
    public function thereAreTheFollowingUniquenesses(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var ConnectToStorageWorker $connectToStorageWorker */
        $connectToStorageWorker = $this->kernel
            ->getContainer()
            ->get('cubalider.connect_to_storage_worker');
        $connectToStorageWorker->connect()->drop();

        /** @var CreateUniquenessWorker $createUniquenessWorker */
        $createUniquenessWorker = $this->kernel
            ->getContainer()
            ->get('cubalider.create_uniqueness_worker');

        foreach ($items as $item) {
            $createUniquenessWorker->create(
                $item['id']
            );
        }
    }
}
