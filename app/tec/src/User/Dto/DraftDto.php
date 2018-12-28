<?php
namespace Tec\User\Dto;

use Gap\Dto\DateTime;
use Gap\Dto\Bin;

class DraftDto extends DtoBase
{
    public $slug;
    public $code;
    public $content;
    public $created;
    public $changed;

    public function init(): void
    {
        $this->code = new Bin();
        $this->created = new DateTime();
        $this->changed = new DateTime();
    }

    public function getTitle(): string
    {
        $title = extract_md_title($this->content);
        if (empty($title)) {
            return 'New draft';
        }
        return $title;
    }
}
