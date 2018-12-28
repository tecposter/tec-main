<?php
namespace Tec\User\Service;

use Gap\Db\Collection;
use Tec\User\Repo\ArticleRepo;

class ArticleService extends ServiceBase
{
    public function listDraft(int $userId): Collection
    {
        return $this->getArticleRepo()->listDraft($userId);
    }

    private function getArticleRepo(): ArticleRepo
    {
        return new ArticleRepo($this->getDmg());
    }
}
