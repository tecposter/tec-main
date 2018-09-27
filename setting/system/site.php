<?php
$collection = new \Gap\Config\ConfigCollection();

$collection
    ->set('site', [
        'default' => [
            'host' => 'www.%baseHost%',
        ],
        'api' => [
            'host' => 'api.%baseHost%',
        ],
        'static' => [
            'host' => 'static.%baseHost%',
            'dir' => '%baseDir%/site/static',
        ],
    ]);

return $collection;
