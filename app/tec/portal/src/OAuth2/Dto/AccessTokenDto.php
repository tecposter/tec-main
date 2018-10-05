<?php
namespace Tec\Portal\OAuth2\Dto;

class AccessTokenDto extends DtoBase
{
    public $token;
    public $appId;
    public $userId;
    public $refresh;
    public $scope;
    public $diff;
    public $created;
    public $expired;
}
