<?php
namespace Tec\Landing\Dto;

use Gap\Dto\DateTime;
use Gap\Dto\Bin;

class DraftDto extends DtoBase
{
    public $code;
    public $slug;
    public $title;
    public $created;
    public $changed;

    public function init(): void
    {
        $this->code = new Bin();
        $this->created = new DateTime();
        $this->changed = new DateTime();
    }
}
