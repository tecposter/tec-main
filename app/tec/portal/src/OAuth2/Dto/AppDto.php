<?php
namespace Tec\Portal\OAuth2\Dto;

class AppDto extends DtoBase
{
    public $appId;
    public $appCode;
    public $appSecret;
    public $appName;
    public $redirectUrl;
    public $privilege;
    public $scope;
    public $created;
    public $changed;
}
