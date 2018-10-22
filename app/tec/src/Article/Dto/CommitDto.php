<?php
namespace Tec\Article\Dto;

use Gap\Dto\Bin;
use Gap\Dto\DateTime;

class CommitDto extends DtoBase
{
    const STATUS_DEFAULT = 'draft';
    const STATUS_PUBLISHED = 'published';

    public $code;
    public $slug;
    public $content;
    public $status;
    public $created;
    public $changed;

    public function init(): void
    {
        $this->code = new Bin();
        $this->created = new DateTime();
        $this->changed = new DateTime();
    }
}
