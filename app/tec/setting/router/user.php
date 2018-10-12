<?php
$collection = new \Gap\Routing\RouteCollection();
/*
$collection
    ->site('default')
    ->access('public')

    ->get('/get/pattern', 'routeName', 'Tec\User\Ui\Entity@show')
    ->post('/post/patter', 'routeName', 'Tec\User\Ui\Entity@post')
    ->getRest('/get-rest/patter', 'routeName', 'Tec\User\Rest\Entity@getRest')
    ->postRest('/post-rest/patter', 'routeName', 'Tec\User\Rest\Entity@postRest')
    ->getOpen('/get-open/patter', 'routeName', 'Tec\User\Open\Entity@getOpen')
    ->postOpen('/post-open/patter', 'routeName', 'Tec\User\Open\Entity@postOpen');
*/
$collection
    ->site('i')

    ->get('/login', 'login', 'Tec\User\Ui\UserUi@login')
    ->post('/login', 'login', 'Tec\User\Ui\UserUi@loginPost')
    ->get('/reg', 'reg', 'Tec\User\Ui\UserUi@reg')
    ->post('/reg', 'reg', 'Tec\User\Ui\UserUi@regPost')

    ->filter('login')
    ->get('/logout', 'logout', 'Tec\User\Ui\UserUi@logout');

return $collection;
