<?php
namespace Tec\Article\Base\Open;

abstract class OpenBase extends \Gap\Base\Open\OpenBase
{
    protected function getAccessToken()
    {
        return $this->request->attributes->get('accessToken');
    }
}
