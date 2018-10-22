<?php
namespace Tec\Landing\Open;

use Gap\Http\JsonResponse;

class HomeOpen extends OpenBase
{
    public function apiIndex(): JsonResponse
    {
        $config = $this->getConfig();
        $openConfig = $config->config('open');
        $siteArr = $config->config('site')->all();
        foreach ($siteArr as &$opts) {
            unset($opts['dir']);
        }
        $routeArr = [];
        foreach ($this->getRouter()->allRoute() as $route) {
            if ($route->mode === 'open') {
                $routeArr[$route->name] = [
                    'method' => $route->method,
                    'name' => $route->name,
                    'pattern' => $route->pattern,
                    'site' => $route->site
                ];
            }
        }

        $res = [
            'appId' => $openConfig->str('appId'),
            'appName' => $openConfig->str('appName'),
            'appDesc' => $openConfig->str('appDesc'),
            'created' => $openConfig->str('created'),
            'changed' => $config->str('changed'),
            'version' => $config->str('vcode'),
            'site' => $siteArr,
            'route' => $routeArr
        ];
        return new JsonResponse($res);
    }
}
