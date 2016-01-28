<?php
/**
 * (C) Brodev Jsc 2014
 * User: quaninte
 * Date: 10/9/14
 * Time: 2:32 PM
 */

namespace TrackerHub\Client;


class CustomerIO extends AbstractClient
{

    /**
     * @var string
     */
    protected $siteId;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $baseUrl = 'https://track.customer.io/api/v1/customers/';

    /**
     * Constructor
     * @param $siteId
     * @param $apiKey
     */
    public function __construct($siteId, $apiKey)
    {
        $this->siteId = $siteId;
        $this->apiKey = $apiKey;
    }

    public function getName()
    {
        return 'customerio';
    }

    /**
     * Identify an user
     * @param $userId
     * @param $params
     * @return bool
     */
    public function identify($userId, array $params = array())
    {
        // Format
        $params = $this->formatParams($params);

        $url = $this->baseUrl . $userId;
        return $this->request($url, $params, 'PUT');
    }

    /**
     * Track an event
     * @param $userId
     * @param $event
     * @param $params
     * @return bool
     */
    public function track($userId, $event, array $params = array())
    {
        // Format
        $params = $this->formatParams($params);

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

        $auth = $this->siteId . ":" . $this->apiKey;

        curl_setopt($curlObj, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curlObj, CURLOPT_USERPWD, $auth);

        $result = $this->sendRequest($curlObj);

        return $result;
    }

    /**
     * Format data to convert date time to timestamp to use with customerio
     * @param $params
     */
    protected function formatParams($params)
    {
        foreach ($params as $key => $value) {
            if ($value instanceof \DateTime) {
                $params[$key] = $value->getTimestamp();
            }
        }

        return $params;
    }

}