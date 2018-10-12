<?php
$collection = new \Gap\Routing\RouteCollection();
/*
$collection
    ->site('default')
    ->access('public')

    ->get('/get/pattern', 'routeName', 'Tec\Article\Ui\Entity@show')
    ->post('/post/patter', 'routeName', 'Tec\Article\Ui\Entity@post')
    ->getRest('/get-rest/patter', 'routeName', 'Tec\Article\Rest\Entity@getRest')
    ->postRest('/post-rest/patter', 'routeName', 'Tec\Article\Rest\Entity@postRest')
    ->getOpen('/get-open/patter', 'routeName', 'Tec\Article\Open\Entity@getOpen')
    ->postOpen('/post-open/patter', 'routeName', 'Tec\Article\Open\Entity@postOpen');
*/
$collection
    ->site('www')
    ->filter('login')

    ->get('/article-create', 'article-create', 'Tec\Article\Ui\ArticleUi@create');

return $collection;
