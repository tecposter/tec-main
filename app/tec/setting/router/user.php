<?php
$collection = new \Gap\Routing\RouteCollection();

$collection
    ->site('i')
    ->noFilter()
    ->get('/login', 'login', 'Tec\User\Ui\UserUi@login')
    ->post('/login', 'login', 'Tec\User\Ui\UserUi@loginPost')
    ->get('/reg', 'reg', 'Tec\User\Ui\UserUi@reg')
    ->post('/reg', 'reg', 'Tec\User\Ui\UserUi@regPost')

    ->filter('login')
    ->get('/logout', 'logout', 'Tec\User\Ui\UserUi@logout')

    ->site('api')
    ->noFilter()
    ->postOpen(
        '/identity-access',
        'identity-access',
        'Tec\User\Open\IdentityOpen@access'
    );

return $collection;
