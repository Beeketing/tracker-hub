<?php
/**
 * (C) Brodev Jsc 2014
 * User: quaninte
 * Date: 10/9/14
 * Time: 2:29 PM
 */

namespace TrackerHub\Client;

abstract class AbstractClient implements ClientInterface
{

    /**
     * Create curl request
     * @param $url
     * @param array|string $params
     * @param bool $post
     * @return resource
     */
    public function createCurlRequest($url, $params, $post = true)
    {
        $curlObj = curl_init();

        if ($post) {
            // Build http post request
            curl_setopt($curlObj, CURLOPT_POST, 1);
            if (is_array($params)) {
                $postData = http_build_query($params);
            } else {
                $postData = $params;
            }
            curl_setopt($curlObj, CURLOPT_POSTFIELDS, $postData);
        } elseif (!empty($params)) {
            // Build http get request
            $url .= '?' . http_build_query($params);
        }

        curl_setopt($curlObj, CURLOPT_URL, $url);


        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);

        return $curlObj;
    }

    /**
     * Send request
     * @param $curlObj
     * @return mixed
     */
    public function sendRequest($curlObj)
    {
        $result = curl_exec($curlObj);
        curl_close ($curlObj);

        return $result;
    }

} 