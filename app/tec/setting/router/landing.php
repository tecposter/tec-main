<?php
$collection = new \Gap\Routing\RouteCollection();
/*
$collection
    ->site('default')
    ->access('public')

    ->get('/get/pattern', 'routeName', 'Tec\Landing\Ui\Entity@show')
    ->post('/post/patter', 'routeName', 'Tec\Landing\Ui\Entity@post')
    ->getRest('/get-rest/patter', 'routeName', 'Tec\Landing\Rest\Entity@getRest')
    ->postRest('/post-rest/patter', 'routeName', 'Tec\Landing\Rest\Entity@postRest')
    ->getOpen('/get-open/patter', 'routeName', 'Tec\Landing\Open\Entity@getOpen')
    ->postOpen('/post-open/patter', 'routeName', 'Tec\Landing\Open\Entity@postOpen');
*/

$collection
    ->site('www')

    ->get('/ui', 'ui', 'Tec\Landing\Ui\HomeUi@uiShow')
    ->get('/', 'home', 'Tec\Landing\Ui\HomeUi@show');

return $collection;
