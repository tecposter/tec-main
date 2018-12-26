<?php
namespace Tec\Article\Enum;

class CommitStatus
{
    const PUBLISHED = 'published';
    const DRAFT = 'draft';
    const DEFAULT = self::DRAFT;
}
