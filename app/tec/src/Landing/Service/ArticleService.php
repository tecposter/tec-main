<?php
namespace Tec\Landing\Service;

use Tec\Landing\Repo\ArticleRepo;
use Gap\Db\MySql\Collection;

class ArticleService extends ServiceBase
{
    public function list(): Collection
    {
        return $this->getArticleRepo()->list();
    }

    public function listDraft(int $userId): Collection
    {
        return $this->getArticleRepo()->listDraft($userId);
    }

    private function getArticleRepo(): ArticleRepo
    {
        return new ArticleRepo($this->getDmg());
    }
}
