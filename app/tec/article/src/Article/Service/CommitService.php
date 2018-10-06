<?php
namespace Tec\Article\Article\Service;

use Tec\Article\Article\Dto\CommitDto;
use Tec\Article\Article\Repo\CommitRepo;

class CommitService extends ServiceBase
{
    public function fetchById(string $commitId): ?CommitDto
    {
        return (new CommitRepo($this->getDmg()))->fetchById($commitId);
    }

    public function update(CommitDto $commitDto): void
    {
        (new CommitRepo($this->getDmg()))->update($commitDto);
    }
}
