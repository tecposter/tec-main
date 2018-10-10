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
        '/article/{zcode:[a-zA-Z0-9-]+}',
        'article-show',
        'Tec\Article\Article\Ui\ArticleUi@show'
    )
    ->get(
        '/create-article',
        'createArticle',
        'Tec\Article\Article\Ui\ArticleUi@create'
    )
    ->get(
        '/update-article-commit/{commitId:[a-zA-Z0-9-]+}',
        'updateArticleCommit',
        'Tec\Article\Article\Ui\CommitUi@update'
    )

    ->site('api')
    ->access('accessToken')

    ->postOpen(
        '/fetch-article-by-id',
        'fetchArticleById',
        'Tec\Article\Article\Open\ArticleOpen@fetchById'
    )

    ->postOpen(
        '/update-article-commit',
        'updateArticleCommit',
        'Tec\Article\Article\Open\CommitOpen@update'
    )
    ->postOpen(
        '/publish-article-commit',
        'publishArticleCommit',
        'Tec\Article\Article\Open\CommitOpen@publish'
    ) ;

return $collection;
