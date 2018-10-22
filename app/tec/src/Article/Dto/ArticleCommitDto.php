<?php
namespace Tec\Article\Dto;

use Gap\Dto\Bin;

class ArticleCommitDto extends DtoBase
{
    public $articleCode;
    public $commitCode;
    public $commitStatus;
    public $content;

    public function init(): void
    {
        $this->commitCode = new Bin();
    }
}
