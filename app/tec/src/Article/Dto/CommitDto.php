<?php
namespace Tec\Article\Dto;

use Gap\Dto\Bin;
use Gap\Dto\DateTime;

class CommitDto extends DtoBase
{
    public $commitId;
    public $userId;
    public $articleId;
    public $code;
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
