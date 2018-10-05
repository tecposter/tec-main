<?php
$collection = new \Gap\Routing\RouteCollection();
/*
$collection
    ->site('default')
    ->access('public')

    ->get('/get/pattern', 'routeName', 'Tec\Portal\Oauth2\Ui\Entity@show')
    ->post('/post/patter', 'routeName', 'Tec\Portal\Oauth2\Ui\Entity@post')
    ->getRest('/get-rest/patter', 'routeName', 'Tec\Portal\Oauth2\Rest\Entity@getRest')
    ->postRest('/post-rest/patter', 'routeName', 'Tec\Portal\Oauth2\Rest\Entity@postRest')
    ->getOpen('/get-open/patter', 'routeName', 'Tec\Portal\Oauth2\Open\Entity@getOpen')
    ->postOpen('/post-open/patter', 'routeName', 'Tec\Portal\Oauth2\Open\Entity@postOpen');
*/

$collection
    ->site('api')
    ->access('public')

    ->postOpen('/access-by-id-token', 'accessByIdToken', 'Tec\Portal\OAuth2\Open\AccessOpen@accessByIdToken');

return $collection;
