<?php
namespace Tec\User\Dto;

use Gap\Dto\Bin;
use Gap\Dto\DateTime;

class AccessTokenDto extends DtoBase
{
    public $token;
    public $refresh;
    public $scope;
    public $created;
    public $expired;

    public function init(): void
    {
        $this->token = new Bin();
        $this->refresh = new Bin();
        $this->created = new DateTime();
        $this->expired = new DateTime();
    }
}
