<?php
$collection = new \Gap\Config\ConfigCollection();

$collection->set('routeFilter', [
    'Tec\Portal\OAuth2\RouteFilter\LoginFilter',
    'Tec\Portal\OAuth2\RouteFilter\AccessFilter'
]);

return $collection;
