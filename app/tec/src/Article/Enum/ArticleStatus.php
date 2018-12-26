<?php
namespace Tec\Article\Enum;

class ArticleStatus
{
    const NORMAL = 'normal';
    const ARCHIVE = 'archive';
    const TRASH = 'trash';
    const DEFAULT = self::NORMAL;
}
