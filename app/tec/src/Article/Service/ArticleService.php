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
        return $this->getArticleRepo()->reqUpdating($userId, $slug);
    }

    public function deleteDraft(int $userId, string $slug): void
    {
        $this->getArticleRepo()->deleteDraft($userId, $slug);
    }

    public function hasSlug(string $slug): bool
    {
        return $this->getArticleRepo()->hasSlug($slug);
    }

    public function fetchCommit(int $userId, string $code): ?CommitDto
    {
        return $this->getArticleRepo()->fetchCommit($userId, $code);
    }

    public function saveCommitContent(int $userId, string $code, string $content): void
    {
        $this->getArticleRepo()->saveCommitContent($userId, $code, $content);
    }

    public function publish(int $userId, string $code, string $slug, string $access): void
    {
        $this->getArticleRepo()->publish($userId, $code, $slug, $access);
    }

    private function getArticleRepo(): ArticleRepo
    {
        return new ArticleRepo($this->getDmg());
    }
}
