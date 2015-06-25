<?php

namespace Muchacuba\ModelBundle\Command;

use Muchacuba\InfoSms\CreateInfoTestWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class MigrateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('muchacuba:migrate');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new \MongoClient($this->getContainer()->getParameter('mongo_server'));

        $oldDb = $client->selectDB("muchacuba_old");
        $newDb = $client->selectDB("muchacuba");

        $oldCollection = $oldDb->selectCollection('infosms_past_infos');
        $newCollection = $newDb->selectCollection('mc_info_sms_infos');


        $oldItems = $oldCollection->find();
        foreach ($oldItems as $oldItem) {
            // Ignore duplicated
            if (in_array($oldItem['_id'], ['5560cfd83a264', '5559ee4fbcaf3'])) {
                continue;
            }

            // Ignore topic "Resultados"
            if (in_array('55573f7ad089b', $oldItem['topics'])) {
                continue;
            }

            // Ignore topic "Liga francia"
            if (in_array($oldItem['_id'], ['556c5de0adfdd', '5559f282f16dc'])) {
                continue;
            }

            // Ignore topic "Champions"
            if (in_array($oldItem['_id'], ['55704dbff31dc'])) {
                continue;
            }

            $topics = [];

            // "Pelota"
            if (in_array('55704c96e0835', $oldItem['topics'])) {
                $topics = ['55704c96e0835'];
            }
            // "Liga Española"
            if (in_array('55573fae42e4d', $oldItem['topics'])) {
                $topics = ['55573fae42e4d'];
            }
            // "Liga Alemana"
            if (in_array($oldItem['_id'], ['556c5c08c7e26', '5560ccc7e787b', '5559f236c6c13'])) {
                $topics[] = '55573fc0e4b4b';
            }
            // "Liga Italiana"
            if (in_array($oldItem['_id'], ['556c954c65057', '55676cad50d25', '5560cdb6d08a1', '555cfb26917c6', '5559f09c70e23', '5557552d8589a', '55574326465c0', '556f181c80784'])) {
                $topics[] = '55573fc0f5a5c';
            }
            // "Liga Inglesa"
            if (in_array($oldItem['_id'], ['556c5e09b8baf', '556c5df285087', '55636d1e17c77', '555ce26d61c7f', '5559f1fb5e58d', '5557422b694b8', '55574326465c0', '557c34084bfde', '55817e04e863f', '5585724cb32b6', '558ab96b1b202'])) {
                $topics[] = '55573fc0e4c3a';
            }
            // "Selecciones nacionales"
            if (in_array('5557400e229c5', $oldItem['topics'])) {
                $topics[] = '5557400e229c5';
            }

            if (!$topics) {
                var_dump($oldItem['_id']);die;
            }

            $newCollection->insert([
                'id' => $oldItem['_id'],
                'body' => $oldItem['body'],
                'topics' => $topics,
                'created' => $oldItem['created']
            ]);
        }
    }
}
