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
    ->noFilter()
    ->get('/article/{articleCode:[a-z0-9-]+}', 'article-show', 'Tec\Article\Ui\ArticleUi@show')

    ->filter('login')
    ->get('/article-req-creating', 'article-req-creating', 'Tec\Article\Ui\ArticleUi@reqCreating')
    ->get(
        '/article-req-updating/{articleCode:[a-z0-9-]+}',
        'article-req-updating',
        'Tec\Article\Ui\ArticleUi@reqUpdating'
    )
    ->get(
        '/article-commit/{commitCode:[a-z0-9-]+}',
        'article-commit',
        'Tec\Article\Ui\ArticleUi@commit'
    );


return $collection;
