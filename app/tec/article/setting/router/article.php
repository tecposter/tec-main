<?php
$collection = new \Gap\Routing\RouteCollection();
/*
$collection
    ->site('default')
    ->access('public')

    ->get('/get/pattern', 'routeName', 'Tec\Article\Article\Ui\Entity@show')
    ->post('/post/patter', 'routeName', 'Tec\Article\Article\Ui\Entity@post')
    ->getRest('/get-rest/patter', 'routeName', 'Tec\Article\Article\Rest\Entity@getRest')
    ->postRest('/post-rest/patter', 'routeName', 'Tec\Article\Article\Rest\Entity@postRest')
    ->getOpen('/get-open/patter', 'routeName', 'Tec\Article\Article\Open\Entity@getOpen')
    ->postOpen('/post-open/patter', 'routeName', 'Tec\Article\Article\Open\Entity@postOpen');
*/

$collection
    ->site('default')
    ->access('login')

    ->get(
        '/article/create',
        'createArticle',
        'Tec\Article\Article\Ui\ArticleUi@create'
    )
    ->get(
        '/article/commit/update/{commitId:[a-zA-Z0-9-]+}',
        'updateArticleCommit',
        'Tec\Article\Article\Ui\CommitUi@update'
    );

return $collection;
