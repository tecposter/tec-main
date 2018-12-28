<?php
namespace Tec\User\Repo;

use Gap\Db\Collection;
use Tec\User\Dto\DraftDto;
use Tec\User\Enum\CommitStatus;

class ArticleRepo extends RepoBase
{
    const ARTICLE_TABLE = 'tec_article';
    const COMMIT_TABLE = 'tec_article_commit';

    public function listDraft(int $userId): Collection
    {
        return $this->cnn->ssb()
            ->select(
                'a.slug',
                'c.code',
                'c.content',
                'a.created',
                'a.changed'
            )
            ->from(self::ARTICLE_TABLE . ' a')
                ->leftJoin(self::COMMIT_TABLE . ' c')
                ->onCond()
                    ->expect('a.articleId')->equal()->expr('c.articleId')
                ->endJoin()
            ->end()
            ->where()
                ->expect('c.userId')->equal()->int($userId)
                ->andExpect('c.status')->equal()->str(CommitStatus::DRAFT)
            ->end()
            ->descOrderBy('c.commitId')
            ->list(DraftDto::class);
    }
}
