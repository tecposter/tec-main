<?php
$collection = new \Gap\Config\ConfigCollection();

$collection
    ->set('site', [
        'www' => [
            'baseUrl' => 'www.%baseHost%',
            'protocol' => 'https'
        ],
        'i' => [
            'baseUrl' => 'i.%baseHost%',
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
