<?php

namespace Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ConnectToStorageInternalWorker
{
    /**
     * @var string
     */
    private $server;

    /**
     * @var string
     */
    private $db;

    /**
     * @var \MongoCollection
     */
    private $collection;

    /**
     * @param string $server
     * @param string $db
     *
     * @Di\InjectParams({
     *     "server" = @Di\Inject("%mongo_server%"),
     *     "db"     = @Di\Inject("%mongo_db%")
     * })
     */
    public function __construct($server, $db) {
        $this->server = $server;
        $this->db = $db;
    }

    /**
     * Connects to mongodb.
     *
     * @return \MongoCollection
     */
    public function connect()
    {
        if (!$this->collection) {
            $client = new \MongoClient($this->server);
            $db = $client->selectDB($this->db);

            $this->collection = $db->selectCollection('mc_info_sms_subscription_low_balance_reminder_logs');

            $this->collection->ensureIndex(
                array('mobile' => 1),
                array('unique' => true)
            );
        }

        return $this->collection;
    }
}
