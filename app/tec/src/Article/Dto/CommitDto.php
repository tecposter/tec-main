<?php
namespace Tec\Article\Dto;

use Gap\Dto\Bin;

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
    }
}
