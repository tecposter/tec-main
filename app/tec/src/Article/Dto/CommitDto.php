<?php
namespace Tec\Article\Dto;

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

    public function getCode(): string
    {
        return bin2hex($this->code);
    }
}
