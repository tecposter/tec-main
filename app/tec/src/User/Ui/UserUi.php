<?php
namespace Tec\User\Ui;

use Gap\Http\Response;
use Gap\Http\RedirectResponse;
use Gap\Http\ResponseInterface;
use Gap\Http\Cookie;

use Tec\User\Service\OpenIdService;
use Tec\User\Service\UserService;
use Tec\User\Dto\RegDto;

class UserUi extends UiBase
{
    public function login(): ResponseInterface
    {
        if ($this->request->cookies->get('idToken')) {
            return new RedirectResponse(
                $this->getRouteUrlBuilder()->routeGet('home')
            );
        }
        return $this->view('page/user/login');
    }

    public function logout(): RedirectResponse
    {
        $homeUrl = $this->getRouteUrlBuilder()->routeGet('home');
        $response = new RedirectResponse($homeUrl);

        $path = '/';
        $domain = '.' . $this->config->str('baseHost');
        $secure = true;
        $httpOnly = true;
        $response->headers->clearCookie('idToken', '/', '.' . $this->config->str('baseHost'), true, true);

        return $response;
    }

    public function reg(): Response
    {
        return $this->view('page/user/reg');
    }

    public function loginPost(): ResponseInterface
    {
        $post = $this->request->request;
        $email = $post->get('email');
        $password = $post->get('password');

        $userService = new UserService($this->app);

        $userDto = $userService->fetchByEmail($email);
        if (!$userDto->verifyPassword($password)) {
            throw new \Exception('login failed');
        }

        // https://symfony.com/blog/new-in-symfony-3-3-cookie-improvements
        $openIdService = new OpenIdService($this->app);
        $idToken = $openIdService->createIdTokenByUser($userDto);

        $homeUrl = $this->getRouteUrlBuilder()->routeGet('home');
        $response = new RedirectResponse($homeUrl);

        $expire = 0;
        $path = '/';
        $domain = '.' . $this->config->str('baseHost');
        $secure = true;
        $httpOnly = true;
        $response->headers->setCookie(new Cookie(
            'idToken',
            $idToken,
            $expire,
            $path,
            $domain,
            $secure,
            $httpOnly
        ));
        return $response;
    }

    public function regPost(): ResponseInterface
    {
        $post = $this->request->request;
        $regDto = new RegDto([
            'email' => $post->get('email'),
            'phone' => $post->get('phone'),
            'zcode' => $post->get('zcode'),
            'fullname' => $post->get('fullname'),
            'password' => $post->get('password'),
        ]);

        $userService = new UserService($this->app);
        $userService->reg($regDto);
        $loginUrl = $this->getRouteUrlBuilder()->routeGet('login');
        return new RedirectResponse($loginUrl);
    }
}
