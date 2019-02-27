<?php
namespace Tec\Landing\Repo;

use Gap\Db\MySql\Collection;

use Tec\Landing\Dto\ArticleDto;

class ArticleRepo extends RepoBase
{
    const ARTICLE_TABLE = 'tec_article_summary';
    const ARTICLE_ACCESS_PUBLIC = 'public';

    public function list(): Collection
    {
        return $this->cnn->ssb()
            ->select(
                'slug',
                'userSlug',
                'userFullname',
                'title',
                'created',
                'changed'
            )
            ->from(self::ARTICLE_TABLE)->end()
            ->where()
                ->expect('access')->equal()->str(self::ARTICLE_ACCESS_PUBLIC)
            ->end()
            ->descOrderBy('commitId')
            ->list(ArticleDto::class);
    }
}
/*
class ArticleRepo extends RepoBase
{
    const ARTICLE_TABLE = 'tec_article';
    const COMMIT_TABLE = 'tec_article_commit';
    const USER_TABLE = 'tec_user';

    const ARTICLE_ACCESS_PUBLIC = 'public';
    const COMMIT_STATUS_PUBLISHED = 'published';

    public function list(): Collection
    {
        return $this->cnn->ssb()
            ->select(
                'a.slug',
                'u.slug userSlug',
                'u.fullname userFullname',
                'c.content',
                'a.created',
                'a.changed'
            )
            ->from(self::ARTICLE_TABLE . ' a')
                ->leftJoin(self::COMMIT_TABLE . ' c')
                ->onCond()
                    ->expect('a.articleId')->equal()->expr('c.articleId')
                    ->andExpect('a.commitId')->equal()->expr('c.commitId')
                ->endJoin()
                ->leftJoin(self::USER_TABLE . ' u')
                ->onCond()
                    ->expect('a.userId')->equal()->expr('u.userId')
                ->endJoin()
            ->end()
            ->where()
                ->expect('a.access')->equal()->str(self::ARTICLE_ACCESS_PUBLIC)
                ->andExpect('c.status')->equal()->str(self::COMMIT_STATUS_PUBLISHED)
            ->end()
            ->descOrderBy('c.commitId')
            ->list(ArticleDto::class);
    }
}
 */
