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
    ->get('/article/{slug:[a-z0-9-]+}', 'article-show', 'Tec\Article\Ui\ArticleUi@show')
    ->get('/markdown', 'markdown', 'Tec\Article\Ui\MarkdownUi@show')

    ->filter('login')
    ->get('/article-req-creating', 'article-req-creating', 'Tec\Article\Ui\ArticleUi@reqCreating')
    ->get(
        '/article-req-updating/{slug:[a-z0-9-]+}',
        'article-req-updating',
        'Tec\Article\Ui\ArticleUi@reqUpdating'
    )
    ->get(
        '/article-update-commit/{code:[a-z0-9-]+}',
        'article-update-commit',
        'Tec\Article\Ui\ArticleUi@updateCommit'
    )
    ->get(
        '/article-delete-draft/{slug:[a-z0-9-]+}',
        'article-delete-draft',
        'Tec\Article\Ui\ArticleUi@deleteDraft'
    )

    ->site('api')
    ->noFilter()
    ->postOpen(
        '/article-fetch-released-content',
        'article-fetch-released-content',
        'Tec\Article\Open\ArticleOpen@fetchReleasedContent'
    )
    

    ->site('api')
    ->filter('accessToken')
    ->postOpen(
        '/article-save-commit-content',
        'article-save-commit-content',
        'Tec\Article\Open\ArticleOpen@saveCommitContent'
    )
    ->postOpen(
        '/article-publish',
        'article-publish',
        'Tec\Article\Open\ArticleOpen@publish'
    );


return $collection;
