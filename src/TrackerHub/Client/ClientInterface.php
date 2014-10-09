<?php
/**
 * (C) Brodev Jsc 2014
 * User: quaninte
 * Date: 10/9/14
 * Time: 2:32 PM
 */

namespace TrackerHub\Client;


interface ClientInterface
{

    /**
     * Identify an user
     * @param $userId
     * @param array $params
     * @return bool
     */
    public function identify($userId, array $params = array());

    /**
     * Track an event
     * @param $userId
     * @param $event
     * @param array $params
     * @return bool
     */
    public function track($userId, $event, array $params = array());

} 