<?php
/**
 * User: ducanh
 * Date: 16/05/2015
 * Time: 11:53
 */

namespace TrackerHub\Client;


class Beeketing extends AbstractClient
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @param $apiKey
     * @param $baseUrl
     */
    public function __construct($apiKey, $baseUrl)
    {
        $this->$baseUrl = $baseUrl;
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
        $url = $this->baseUrl . $userId;
        return $this->request($url, $params, 'POST');
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
        $url = $this->baseUrl . $userId . '/events';

        $requestParams = array(
            'name' => $event,
            'data' => $params,
        );

        return $this->request($url, $requestParams, 'POST');
    }

    /**
     * Request
     * @param $url
     * @param $params
     * @param string $method
     * @return mixed
     */
    protected function request($url, $params, $method = 'POST')
    {
        $curlObj = $this->createCurlRequest($url, $params);

        curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('api: '. $this->apiKey));

        $result = $this->sendRequest($curlObj);

        return $result;
    }
}