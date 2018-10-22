<?php
namespace Tec\Article\Service;

use Tec\Article\Dto\CommitDto;
use Tec\Article\Repo\CommitRepo;

class CommitService extends ServiceBase
{
    public function fetchByCode(string $code): ?CommitDto
    {
        return (new CommitRepo($this->getDmg()))->fetchByCode($code);
    }
}
