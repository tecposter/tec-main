<?php
namespace Tec\Article\Dto;

use Gap\Dto\DateTime;

class DetailDto extends ArticleBaseDto
{
    public $slug;
    public $userSlug;
    public $userFullname;
    public $content;
    public $access;
    public $status;
    public $created;
    public $changed;

    public function init(): void
    {
        $this->created = new DateTime();
        $this->changed = new DateTime();
    }

    public function getTitle(): string
    {
        return extract_md_title($this->content);
        /*
        $matched = preg_match('/# ([^#\n]+)/', $this->content, $matches);
        if (!$matched) {
            return '';
        }
        return trim($matches[1]);
         */
    }
}
