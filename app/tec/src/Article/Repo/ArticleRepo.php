<?php
namespace Tec\Article\Repo;

use Tec\Article\Dto\ArticleDetailDto;

class ArticleRepo extends RepoBase
{
    private $table = 'tec_article';

    public function fetchDetailByCode(string $code): ?ArticleDetailDto
    {
        if (empty($code)) {
            throw new \Exception('code cannot be empty');
        }

        $articleTable = $this->table;
        $commitTable = 'tec_article_commit';
        $userTable = 'tec_user';

        return $this->cnn->ssb()
            ->select(
                'a.articleId',
                'a.code',
                'a.commitId',
                'a.userId',
                'u.code userCode',
                'u.fullname userFullname',
                'c.content',
                'a.access',
                'a.status',
                'a.created',
                'a.changed'
            )
            ->from("{$articleTable} a")
                ->leftJoin("$commitTable c")
                ->onCond()
                    ->expect('a.articleId')->equal()->expr('c.articleId')
                ->endJoin()
                ->leftJoin("$userTable u")
                ->onCond()
                    ->expect('a.userId')->equal()->expr('u.userId')
                ->endJoin()
            ->end()
            ->where()
                ->expect('a.code')->equal()->str($code)
            ->end()
            ->fetch(ArticleDetailDto::class);
    }
}
