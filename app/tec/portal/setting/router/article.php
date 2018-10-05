<?php
$collection = new \Gap\Routing\RouteCollection();
/*
$collection
    ->site('default')
    ->access('public')

    ->get('/get/pattern', 'routeName', 'Tec\Portal\Article\Ui\Entity@show')
    ->post('/post/patter', 'routeName', 'Tec\Portal\Article\Ui\Entity@post')
    ->getRest('/get-rest/patter', 'routeName', 'Tec\Portal\Article\Rest\Entity@getRest')
    ->postRest('/post-rest/patter', 'routeName', 'Tec\Portal\Article\Rest\Entity@postRest')
    ->getOpen('/get-open/patter', 'routeName', 'Tec\Portal\Article\Open\Entity@getOpen')
    ->postOpen('/post-open/patter', 'routeName', 'Tec\Portal\Article\Open\Entity@postOpen');
*/

$collection
    ->site('default')
    ->access('login')

    ->get(
        '/article/create',
        'createArticle',
        'Tec\Portal\Article\Ui\ArticleUi@create'
    );

return $collection;
