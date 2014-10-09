<?php
/**
 * (C) Brodev Jsc 2014
 * User: quaninte
 * Date: 10/9/14
 * Time: 2:31 PM
 */

namespace TrackerHub;


use TrackerHub\Client\ClientInterface;

class TrackerHub
{

    /**
     * @var array
     */
    protected $clients = array();

    /**
     * Add new client
     * @param ClientInterface $client
     */
    public function addClient(ClientInterface $client)
    {
        $this->clients[] = $client;
    }

    /**
     * Identify an user
     * @param $userId
     * @param $params
     * @return bool
     */
    public function identify($userId, array $params = array()) {
        /** @var ClientInterface $client */
        foreach ($this->clients as $client) {
            $client->identify($userId, $params);
        }
    }

    /**
     * Track an event
     * @param $userId
     * @param $event
     * @param array $params
     * @return bool
     */
    public function track($userId, $event, array $params = array()) {
        /** @var ClientInterface $client */
        foreach ($this->clients as $client) {
            $client->track($userId, $event, $params);
        }
    }

} 