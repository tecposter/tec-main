<?php
namespace Tec\Article\Article\Repo;

use Gap\Dto\DateTime;

use Tec\Article\Article\Dto\CommitDto;

class CommitRepo extends RepoBase
{
    private $statusDefault = 'draft';
    private $table = 'tec_article_commit';

    public function update(CommitDto $commitDto): void
    {
        if (!$commitDto->commitId) {
            throw new \Exception('update commitId not found');
        }

        $commitDto->changed = new DateTime();
        $this->cnn->usb()
            ->update($this->table)->end()
            ->set('content')->str($commitDto->content)
            ->set('changed')->dateTime($commitDto->changed)
            ->where()
                ->expect('commitId')->equal()->str($commitDto->commitId)
            ->end()
            ->execute();
    }

    public function fetchById(string $commitId): ?CommitDto
    {
        return $this->cnn->ssb()
            ->select(
                ...$this->getFields()
            )
            ->from($this->table)->end()
            ->where()
                ->expect('commitId')->equal()->str($commitId)
            ->end()
            ->fetch(CommitDto::class);
    }

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
                ...$this->getFields()
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

    private function getFields(): array
    {
        return [
            'articleId',
            'commitId',
            'content',
            'userId',
            'status',
            'created',
            'changed'
        ];
    }
}
