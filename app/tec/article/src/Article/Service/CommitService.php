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

    public function publish(string $userId, string $articleId, string $commitId, string $zcode, string $access): void
    {
        $commitRepo = new CommitRepo($this->getDmg());
        $commitDto = $commitRepo->fetchById($commitId);
        if ($commitDto->status === 'published') {
            throw new \Exception('The commit has already been published');
        }

        if ($commitDto->userId !== $userId) {
            throw new \Exception('The commit does not belong to the current user');
        }
        if ($commitDto->articleId !== $articleId) {
            throw new \Exception('The commit does not belong to the current article');
        }

        $commitRepo->publish($articleId, $commitId, $zcode, $access);
    }
}
