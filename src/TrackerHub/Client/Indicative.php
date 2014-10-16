<?php
/**
 * Copyright Brodev Software.
 * (c) Quan MT <quanmt@brodev.com>
 */


namespace TrackerHub\Client;


class Indicative extends AbstractClient
{

    /**
     * @var string
     */
    protected $trackUrl = 'https://api.indicative.com/service/event';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * Constructor
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Identify an user
     * @param $userId
     * @param array $params
     * @return bool
     */
    public function identify($userId, array $params = array())
    {
        // Do nothing because don't support identify
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
        $requestData = array(
            'apiKey' => $this->apiKey,
            'eventName' => $event,
            'eventUniqueId' => $userId,
        );

        // Indicative doesn't allow empty array as properties
        if (count($params)) {
            $requestData['properties'] = $params;
        }

        $curlObj = $this->createCurlRequest($this->trackUrl, $requestData);

        // Post json
        $dataString = json_encode($requestData);

        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($dataString))
        );

        $response = $this->sendRequest($curlObj);

        return $response;
    }
}