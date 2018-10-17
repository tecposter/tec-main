<?php
namespace Tec\User\Dto;

use Gap\Dto\DateTime;

class IdentityDto extends DtoBase
{
    public $identityId;
    public $code;
    public $data;
    public $created;
    public $expired;

    public function init(): void
    {
        $this->created = new DateTime();
        $this->expired = new DateTime();
    }
}
