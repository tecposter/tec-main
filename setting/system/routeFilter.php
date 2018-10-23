<?php
$collection = new \Gap\Config\ConfigCollection();

$collection->set('routeFilter', [
    'login' => 'Tec\User\RouteFilter\LoginFilter',
    'accessToken' => 'Tec\User\RouteFilter\AccessTokenFilter'
]);

return $collection;
