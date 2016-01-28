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
    protected $clients = [];

    /**
     * Only track with these clients
     * @var array
     */
    protected $includeClients = [];

    /**
     * Track all clients except these clients
     * @var array
     */
    protected $excludeClients = [];

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
    public function identify($userId, array $params = array())
    {
        $ignoreClients = [];
        $clients = $this->clients;
        /** @var ClientInterface $client */

        // Get clients list
        if (!empty($this->includeClients)) {
            // Reset list
            $clients = [];
            foreach ($this->clients as $client) {
                if (in_array($client->getName(), $this->includeClients)) {
                    $clients[] = $client;
                }
            }
        }

        // Get ignore lists
        if (!empty($this->excludeClients)) {
            $ignoreClients = $this->excludeClients;
        }

        foreach ($clients as $client) {
            if (in_array($client->getName(), $ignoreClients)) {
                continue;
            }

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
    public function track($userId, $event, array $params = array())
    {
        /** @var ClientInterface $client */
        foreach ($this->clients as $client) {
            $client->track($userId, $event, $params);
        }
    }

    /**
     * @param array $includeClients
     */
    public function setIncludeClients($includeClients)
    {
        $this->includeClients = $includeClients;
    }

    /**
     * @param array $excludeClients
     */
    public function setExcludeClients($excludeClients)
    {
        $this->excludeClients = $excludeClients;
    }

} 