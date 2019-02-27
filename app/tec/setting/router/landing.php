<?php
$collection = new \Gap\Routing\RouteCollection();

$collection
    ->site('www')
    ->noFilter()
    ->get('/ui', 'ui', 'Tec\Landing\Ui\HomeUi@uiShow')
    ->get('/', 'home', 'Tec\Landing\Ui\HomeUi@show')

    ->filter('login')
    ->get(
        '/draft',
        'draft',
        'Tec\Landing\Ui\ArticleUi@draft'
    )


    ->site('api')
    ->noFilter()
    ->postOpen('/', 'apiIndex', 'Tec\Landing\Open\HomeOpen@apiIndex');

return $collection;
