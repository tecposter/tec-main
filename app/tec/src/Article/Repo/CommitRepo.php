<?php
namespace Tec\Article\Repo;

use Tec\Article\Dto\CommitDto;
use Gap\Dto\DateTime;

class CommitRepo extends RepoBase
{
    private $table = 'tec_article_commit';
    private $statusDefault = 'draft';

    public function create(int $userId, int $articleId): CommitDto
    {
        if ($userId <= 0) {
            throw new \Exception('error userId');
        }
        if ($articleId <= 0) {
            throw new \Exception('error articleId');
        }

        $commit = new CommitDto([
            'userId' => $userId,
            'articleId' => $articleId,
            'code' => $this->createCode(),
            'content' => '',
            'status' => $this->statusDefault,
            'created' => new DateTime(),
            'changed' => new DateTime()
        ]);

        $this->cnn->isb()
            ->insert($this->table)
            ->field(...$this->getFields())
            ->value()
                ->addInt($commit->userId)
                ->addInt($commit->articleId)
                ->addStr($commit->code->getBin())
                ->addStr($commit->content)
                ->addStr($commit->status)
                ->addDateTime($commit->created)
                ->addDateTime($commit->changed)
            ->end()
            ->execute();


        $commit->commitId = $this->cnn->lastInsertId();
        return $commit;
    }

    private function createCode(): string
    {
        //$timeHex = dechex((microtime(true) * 10 ** 4) & 0xffffff);
        $timeHex = dechex(microtime(true) * 10 ** 4);
        $paded = str_pad($timeHex, 12, '0', STR_PAD_LEFT);
        return hex2bin($paded) . random_bytes(2);
    }

    private function getFields(): array
    {
        return [
            'userId',
            'articleId',
            'code',
            'content',
            'status',
            'created',
            'changed'
        ];
    }
}
