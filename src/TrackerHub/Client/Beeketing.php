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
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    public function getName()
    {
        return 'beeketing';
    }

    /**
     * Identify an user
     * @param $userId
     * @param array $params
     * @return bool
     */
    public function identify($userId, array $params = array())
    {
        $url = $this->baseUrl . '/bk/api/contacts.json';
        $params['distinct_id'] = $userId;
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
        $url = $this->baseUrl . '/bk/api/actions.json';

        // Properties
        if (isset($params['properties'])) {
            unset($params['properties']);
        }

        $requestParams = array(
            'distinct_id' => $userId,
            'event' => $event,
            'params' => $params,
        );

        return $this->request($url, $requestParams, 'POST');
    }

    /**
     * Request
     * @inheritdoc
     */
    protected function request($url, $params, $method = 'GET')
    {
        $isPost = (bool) ($method == 'POST');
        if ($isPost) {
            $params = json_encode($params);
        }

        $curlObj = $this->createCurlRequest($url, $params, $isPost);

        curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-Beeketing-Api-Key: ' . $this->apiKey
        ));

        list ($httpCode, $result) = $this->sendRequest($curlObj);

        if ($httpCode !== 200) {
            throw new \Exception(sprintf(
                'Failed to sent request to beeketing track api, status code: %s, response: %s',
                $httpCode,
                $result
            ));
        }

        return $result;
    }

    /**
     * Send request
     * @param $curlObj
     * @return mixed
     */
    public function sendRequest($curlObj)
    {
        $result = curl_exec($curlObj);
        $httpCode = curl_getinfo($curlObj, CURLINFO_HTTP_CODE);
        curl_close($curlObj);

        return [
            $httpCode,
            $result
        ];
    }
}