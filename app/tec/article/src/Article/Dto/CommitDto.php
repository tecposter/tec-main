<?php
namespace Tec\Article\Article\Dto;

class CommitDto extends DtoBase
{
    public $articleId;
    public $commitId;
    public $userId;
    public $content;
    public $status;
    public $created;
    public $changed;
}
