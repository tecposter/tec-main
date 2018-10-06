<?php
$collection = new \Gap\Config\ConfigCollection();

$collection
    ->set('site', [
        'default' => [
            'baseUrl' => 'www.%baseHost%',
            'protocol' => 'https'
        ],
        'api' => [
            'baseUrl' => 'api.%baseHost%',
            'protocol' => 'https'
        ],
        'static' => [
            'baseUrl' => 'static.%baseHost%',
            'dir' => '%baseDir%/site/static',
            'protocol' => 'https'
        ],
    ]);

return $collection;
