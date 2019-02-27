<?php
namespace Tec\Landing\Dto;

use Gap\Dto\DateTime;

class ArticleDto extends DtoBase
{
    public $slug;
    public $title;
    public $userSlug;
    public $userFullname;
    public $created;
    public $changed;

    public function init(): void
    {
        $this->created = new DateTime();
        $this->changed = new DateTime();
    }

    public function getTitle(): string
    {
        return $this->title;
    }
    /*
    public function getTitle(): string
    {
        $matched = preg_match('/# ([^#\n]+)/', $this->content, $matches);
        if (!$matched) {
            return '';
        }
        return trim($matches[1]);
    }
     */
}
