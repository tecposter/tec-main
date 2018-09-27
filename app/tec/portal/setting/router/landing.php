<?php
$collection = new \Gap\Routing\RouteCollection();
/*
$collection
    ->site('default')
    ->access('public')

    ->get('/get/pattern', 'routeName', 'Tec\Portal\Landing\Ui\Entity@show')
    ->post('/post/patter', 'routeName', 'Tec\Portal\Landing\Ui\Entity@post')
    ->getRest('/get-rest/patter', 'routeName', 'Tec\Portal\Landing\Rest\Entity@getRest')
    ->postRest('/post-rest/patter', 'routeName', 'Tec\Portal\Landing\Rest\Entity@postRest')
    ->getOpen('/get-open/patter', 'routeName', 'Tec\Portal\Landing\Open\Entity@getOpen')
    ->postOpen('/post-open/patter', 'routeName', 'Tec\Portal\Landing\Open\Entity@postOpen');
*/
$collection
    ->site('default')
    ->access('public')

    ->get('/', 'home', 'Tec\Portal\Landing\Ui\HomeUi@show');

return $collection;
