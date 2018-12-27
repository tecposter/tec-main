<?php
namespace Tec\User\Ui;

use Gap\Http\Response;
use Gap\Http\RedirectResponse;
use Gap\Http\ResponseInterface;
use Gap\Http\Cookie;

use Tec\User\Service\IdentityService;
use Tec\User\Service\IdTokenService;
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
        $response->headers->clearCookie(
            'idToken',
            $path,
            $domain,
            $secure,
            $httpOnly
        );

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

        $user = $userService->fetchByEmail($email);
        if (empty($user)) {
            throw new \Exception('user not found');
        }

        if (!$user->verifyPassword($password)) {
            throw new \Exception('login failed');
        }

        // https://symfony.com/blog/new-in-symfony-3-3-cookie-improvements
        //$identityService = new IdentityService($this->app);
        //$idToken = $identityService->createIdTokenByUser($user);

        $ttl = new \DateInterval('P1M');
        $identity = $this->getIdentityService()->create(
            [
                'userId' => $user->userId,
                'fullname' => $user->fullname
            ],
            $ttl
        );
        $idToken = $this->getIdTokenService()->create($identity->code, $ttl);
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
            'slug' => $post->get('slug'),
            'fullname' => $post->get('fullname'),
            'password' => $post->get('password'),
        ]);

        $userService = new UserService($this->app);
        $userService->reg($regDto);
        $loginUrl = $this->getRouteUrlBuilder()->routeGet('login');
        return new RedirectResponse($loginUrl);
    }

    private function getIdTokenService(): IdTokenService
    {
        return new IdTokenService($this->getApp());
    }

    private function getIdentityService(): IdentityService
    {
        return new IdentityService($this->getApp());
    }
}
