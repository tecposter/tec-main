<?php
namespace Tec\Article\Service;

use Tec\Article\Dto\DetailDto;
use Tec\Article\Dto\CommitDto;

use Tec\Article\Repo\ArticleRepo;

class ArticleService extends ServiceBase
{
    public function fetchDetail(string $articleCode): ?DetailDto
    {
        return (new ArticleRepo($this->getDmg()))->fetchDetail($articleCode);
    }

    public function reqCreating(string $userId): string
    {
        return (new ArticleRepo($this->getDmg()))->createCommit($userId);
    }

    public function reqUpdating(int $userId, string $articleCode): string
    {
    }

    public function fetchCommit(string $commitCode): CommitDto
    {
    }

    public function updateContent(string $commitCode, string $content): void
    {
    }

    public function publish(string $commitCode, string $articleCode): void
    {
    }

    private function getArticleRepo(): ArticleRepo
    {
        return new ArticleRepo($this->getDmg());
    }
}
