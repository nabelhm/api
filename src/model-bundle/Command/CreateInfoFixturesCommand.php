<?php

namespace Muchacuba\ModelBundle\Command;

use Muchacuba\InfoSms\CollectTopicsApiWorker;
use Muchacuba\InfoSms\CreateInfoApiWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CreateInfoFixturesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('muchacuba:fixtures:create-infos')
            ->setDescription('Create info fixtures');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var CollectTopicsApiWorker $collectTopicsApiWorker */
        $collectTopicsApiWorker = $this->getContainer()
            ->get('muchacuba.info_sms.collect_topics_api_worker');

        /** @var CreateInfoApiWorker $createInfoApiWorker */
        $createInfoApiWorker = $this->getContainer()
            ->get('muchacuba.info_sms.create_info_api_worker');

        /** @var \Faker\Generator $generator */
        $generator = $this
            ->getContainer()
            ->get('faker.generator');

        $topics = iterator_to_array($collectTopicsApiWorker->collect());

        if (count($topics) > 0) {
            for ($i = 0; $i <= rand(3, 6); $i++) {
                $infoTopics = [];
                for ($j = 0; $j <= rand(1, 3); $j++) {
                    $topic = $topics[rand(0, count($topics) - 1)]['id'];
                    if (!in_array($topic, $infoTopics)) {
                        $infoTopics[] = $topic;
                    }
                }

                $createInfoApiWorker->create(
                    $generator->paragraphs(2, true),
                    $infoTopics
                );
            }
        }
    }
}
