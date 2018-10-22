<?php
namespace Tec\Article\Service;

use Tec\Article\Dto\DetailDto;
use Tec\Article\Dto\CommitDto;

use Tec\Article\Repo\ArticleRepo;

class ArticleService extends ServiceBase
{
    public function fetchDetail(string $slug): ?DetailDto
    {
        return $this->getArticleRepo()->fetchDetail($slug);
    }

    public function reqCreating(int $userId): string
    {
        return $this->getArticleRepo()->reqCreating($userId);
    }

    public function reqUpdating(int $userId, string $slug): string
    {
    }

    public function fetchCommit(int $userId, string $code): ?CommitDto
    {
        return $this->getArticleRepo()->fetchCommit($userId, $code);
    }

    public function updateCommitContent(int $userId, string $code, string $content): void
    {
    }

    public function publish(int $userId, string $code, string $slug): void
    {
    }

    private function getArticleRepo(): ArticleRepo
    {
        return new ArticleRepo($this->getDmg());
    }
}
