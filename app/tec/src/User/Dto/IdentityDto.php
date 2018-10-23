<?php
namespace Tec\User\Dto;

use Gap\Dto\DateTime;
use Gap\Dto\Bin;
use Gap\Dto\Json;

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
        $this->code = new Bin();
        $this->data = new Json();
    }
}
