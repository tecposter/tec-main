<?php
$collection = new \Gap\Config\ConfigCollection();

$collection->set('cache', [
    'default' => [
        'adapter' => 'PSR16',
        'host' => '%local.cache.host%',
        'database' => 1,
    ],
    'i18n' => [
        'adapter' => 'PSR16',
        'host' => '%local.cache.host%',
        'database' => 3,
    ],
    'meta' => [
        'adapter' => 'PSR16',
        'host' => '%local.cache.host%',
        'database' => 4,
    ],
    'oauth2' => [
        'adapter' => 'PSR16',
        'host' => '%local.cache.host%',
        'database' => 5,
    ]
]);

return $collection;
