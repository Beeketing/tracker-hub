<?php
use TrackerHub\TrackerHub;
use TrackerHub\Client\CustomerIO;
use TrackerHub\Client\Mixpanel;

// Require lib
require __DIR__ . '/../src/TrackerHub/TrackerHub.php';

// Config
require __DIR__ . '/autoload.php';
require __DIR__ . '/config.default.php';
if (file_exists(__DIR__ . '/config.php')) {
    include __DIR__ . '/config.php';
    $config = array_merge($defaultConfig, $config);
} else {
    $config = $defaultConfig;
}

// Create hub
$trackerHub = new TrackerHub();

// Add customer.io client
$customerIOClient = new CustomerIO($config['customer_io']['site_id'], $config['customer_io']['api_key']);
$trackerHub->addClient($customerIOClient);

// Add mixpanel client
$mixpanelClient = new Mixpanel($config['mixpanel']['write_token']);
//$trackerHub->addClient($mixpanelClient);

// Identify a user
$user = array(
    'user_id' => 101,
    'params' => array(
        'first_name' => 'Quan',
        'last_name' => 'MT',
        'email' => 'quan@beeketing.com',
    ),
);
//$trackerHub->identify($user['user_id'], $user['params']);

// Track an event
$event = array(
    'user_id' => 101,
    'event' => 'Add Offer',
    'params' => array(
        'type' => 'Cross-sell',
    ),
);
$trackerHub->track($event['user_id'], $event['event'], $event['params']);