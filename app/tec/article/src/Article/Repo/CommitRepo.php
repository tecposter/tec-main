<?php
namespace Tec\Article\Article\Repo;

use Gap\Dto\DateTime;

use Tec\Article\Article\Dto\CommitDto;

class CommitRepo extends RepoBase
{
    private $statusDefault = 'draft';
    private $table = 'tec_article_commit';

    public function create(CommitDto $commitDto): void
    {
        if (!$commitDto->userId) {
            throw new \Exception('cannot find userId in commit');
        }

        if (!$commitDto->articleId) {
            throw new \Exception('cannot find articleId in commit');
        }

        $now = new DateTime();
        $commitDto->created = $now;
        $commitDto->changed = $now;
        $commitDto->commitId = $this->cnn->zid();
        if (!$commitDto->status) {
            $commitDto->status = $this->statusDefault;
        }
        if (!$commitDto->content) {
            $commitDto->content = '';
        }

        $this->cnn->isb()
            ->insert($this->table)
            ->field(
                'articleId',
                'commitId',
                'content',
                'userId',
                'status',
                'created',
                'changed'
            )
            ->value()
                ->addStr($commitDto->articleId)
                ->addStr($commitDto->commitId)
                ->addStr($commitDto->content)
                ->addStr($commitDto->userId)
                ->addStr($commitDto->status)
                ->addDateTime($commitDto->created)
                ->addDateTime($commitDto->changed)
            ->end()
            ->execute();
    }
}
