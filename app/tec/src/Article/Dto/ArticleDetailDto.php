<?php
namespace Tec\Article\Dto;

class ArticleDetailDto extends DtoBase
{
    public $articleId;
    public $code;
    public $commitId;
    public $userId;
    public $userCode;
    public $userFullname;
    public $content;
    public $access;
    public $status;
    public $created;
    public $changed;
    public function getTitle(): string
    {
        $matched = preg_match('/# ([^#\n]+)/', $this->content, $matches);
        if (!$matched) {
            return '';
        }
        return trim($matches[1]);
    }
}
