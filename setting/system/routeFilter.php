<?php
$collection = new \Gap\Config\ConfigCollection();

$collection->set('routeFilter', [
    'login' => 'Tec\User\RouteFilter\LoginFilter',
    //'accessToken' => 'Tec\OAuth2\RouteFilter\AccessTokenFilter'
]);

return $collection;
