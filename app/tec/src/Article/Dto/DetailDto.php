<?php
namespace Tec\Article\Dto;

use Gap\Dto\DateTime;

class DetailDto extends DtoBase
{
    const STATUS_DEFAULT = 'normal';
    const ACCESS_DEFAULT = 'private';

    public $articleCode;
    public $userCode;
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
        $matched = preg_match('/# ([^#\n]+)/', $this->content, $matches);
        if (!$matched) {
            return '';
        }
        return trim($matches[1]);
    }
}
