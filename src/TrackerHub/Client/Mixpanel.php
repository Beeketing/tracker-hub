<?php
/**
 * (C) Brodev Jsc 2014
 * User: quaninte
 * Date: 10/9/14
 * Time: 2:32 PM
 */

namespace TrackerHub\Client;


class Mixpanel extends AbstractClient
{

    /**
     * @var string
     */
    protected $token;

    /**
     * Track user link
     * @var string
     */
    protected $engageUrl = 'http://api.mixpanel.com/engage/';

    /**
     * Track event link
     * @var string
     */
    protected $trackUrl = 'http://api.mixpanel.com/track/';

    /**
     * Constructor
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Identify an user
     * @param $userId
     * @param $params
     * @return bool
     */
    public function identify($userId, array $params = array())
    {
        /**
         * Sample data
         * {
        "$token": "36ada5b10da39a1347559321baf13063",
        "$distinct_id": "13793",
        "$ip": "123.123.123.123",
        OPERATION_NAME: OPERATION_VALUE
        }
         */
        $requestParams = $this->buildEngageParams($userId, $params);

        // Only use $set for now
        $requestParams['$set'] = $params;

        return $this->request($this->engageUrl, $requestParams);
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
        /**
         * Sample data
         * {
        "event": "Signed Up",
        "properties": {
        // "distinct_id" and "token" are
        // special properties, described below.
        "distinct_id": "13793",
        "token": "e3bc4100330c35722740fb8c6f5abddc",
        "Referred By": "Friend"
        }
        }
         */

        $requestParams = array(
            'event' => $event,
            'properties' => $params,
        );

        if ($userId) {
            $requestParams['properties']['distinct_id'] = $userId;
        }
        $requestParams['properties']['token'] = $this->token;

        // If this request has revenue property
        if (isset($params['revenue']) && $params['revenue']) {
            // Send a revenue request
            // If not provided => time = now()
            $params['time'] = (isset($params['time']) && $params['time']) ? $params['time'] : time();

            $this->recordRevenue($userId, $params['revenue']);
        }

        return $this->request($this->trackUrl, $requestParams);
    }

    /**
     * Record revenue from user
     * @param $userId
     * @param $revenue
     * @param null $time
     * @return mixed
     */
    protected function recordRevenue($userId, $revenue, $time = null)
    {
        // If time is not provided
        if (!$time) {
            // Time is now
            $time = time();
        }
        $dateTime = new \DateTime();
        $dateTime->setTimestamp($time);

        $requestParams = $this->buildEngageParams($userId);

        // Build append transaction params
        $requestParams['$append'] = [
            '$transactions' => [
                '$time' => $dateTime->format('Y-m-dTh:i:s'),
                '$amount' => $revenue,
            ],
        ];

        return $this->request($this->engageUrl, $requestParams);
    }

    /**
     * Build engage params to send request
     * @param $userId
     * @param $params
     * @return array
     */
    protected function buildEngageParams($userId, $params = array())
    {
        $requestParams = array(
            '$token' => $this->token,
            '$distinct_id' => $userId,
        );

        if (isset($params['ip'])) {
            $requestParams['$ip'] = $params['ip'];
            unset($params['ip']);
        }

        return $requestParams;
    }

    /**
     * Get data to send
     * @param array $params
     * @return array
     */
    public function getData(array $params)
    {
        return array(
            'data' => base64_encode(json_encode($params)),
        );
    }

    /**
     * Send a request
     * @param $url
     * @param array $params
     * @return mixed
     */
    protected function request($url, array $params)
    {
        $data = $this->getData($params);

        $curlObj = $this->createCurlRequest($url, $data);
        $result = $this->sendRequest($curlObj);

        return $result;
    }
}