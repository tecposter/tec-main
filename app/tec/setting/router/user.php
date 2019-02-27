<?php
$collection = new \Gap\Routing\RouteCollection();

$collection
    ->site('i')
    ->noFilter()
    ->get('/login', 'login', 'Tec\User\Ui\UserUi@login')
    ->post('/login', 'login', 'Tec\User\Ui\UserUi@loginPost')
    ->get('/reg', 'reg', 'Tec\User\Ui\UserUi@reg')
    ->post('/reg', 'reg', 'Tec\User\Ui\UserUi@regPost')
    ->get('/logout', 'logout', 'Tec\User\Ui\UserUi@logout')

    ->filter('login')
    ->get('/draft', 'draft', 'Tec\User\Ui\ArticleUi@draft')

    ->site('api')
    ->noFilter()
    ->postOpen(
        '/identity-access',
        'identity-access',
        'Tec\User\Open\IdentityOpen@access'
    )

    ->site('api')
    ->filter('accessToken')
    ->postOpen(
        '/refresh-access-token',
        'refresh-access-token',
        'Tec\User\Open\IdentityOpen@refreshAccessToken'
    )
    ->postOpen(
        '/refresh-id-token',
        'refresh-id-token',
        'Tec\User\Open\IdentityOpen@refreshIdToken'
    );

return $collection;
