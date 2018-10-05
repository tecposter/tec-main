<?php
$collection = new \Gap\Config\ConfigCollection;

$collection->set('open', [
    'appId' => 'tecmain',
    'appSecret' => 'ifewoj888777fdlksaj',
    'appName' => 'TecPoster',
    'appDesc' => '',
    'redirectUrl' => 'https://www.wecfin.com/oauth2/redirect',
    'created' => '2018-01-01 00:00:00',
    'publicKey' => 'file://%baseDir%/setting/rsa/public.pem',
    'privateKey' => 'file://%baseDir%/setting/rsa/private.pem'
]);

return $collection;
