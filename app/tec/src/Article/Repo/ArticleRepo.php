<?php
namespace Tec\Article\Repo;

use Tec\Article\Dto\DetailDto;
use Tec\Article\Dto\CommitDto;

use Gap\Dto\DateTime;
use Gap\Dto\TimeUniq;

class ArticleRepo extends RepoBase
{
    private $table = 'tec_article';
    private $statusDefault = 'normal';
    private $accessDefault = 'private';

    public function fetchDetail(string $code): ?DetailDto
    {
        if (empty($code)) {
            throw new \Exception('code cannot be empty');
        }

        $articleTable = $this->table;
        $commitTable = 'tec_article_commit';
        $userTable = 'tec_user';

        return $this->cnn->ssb()
            ->select(
                'a.code articleCode',
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
            ->fetch(DetailDto::class);
    }

    public function createCommit(int $userId): CommitDto
    {
        if ($userId <= 0) {
            throw new \Exception('error userId');
        }

        $trans = $this->cnn->trans();
        $trans->begin();

        //$code = (new TimeUniq())->getBin() . random_bytes(1);
        $code = $this->createCode();
        $emptyCommitId = 0;
        $access = $this->accessDefault;
        $status = $this->statusDefault;
        $created = new DateTime();
        $changed = new DateTime();

        try {
            $this->cnn->isb()
                ->insert($this->table)
                ->field(...$this->getFields())
                ->value()
                    ->addStr($code)
                    ->addInt($emptyCommitId)
                    ->addInt($userId)
                    ->addStr($access)
                    ->addStr($status)
                    ->addDateTime($created)
                    ->addDateTime($changed)
                ->end()
                ->execute();

            $articleId = $this->cnn->lastInsertId();
            $commit = (new CommitRepo($this->dmg))->create($userId, $articleId);
        } catch (\Exception $e) {
            $trans->rollback();
            throw $e;
        }

        $trans->commit();
        return $commit;
    }

    private function createCode(): string
    {
        $timeHex = dechex(microtime(true) * 10 ** 4);
        return $timeHex . bin2hex(random_bytes(2));
    }

    private function getFields(): array
    {
        return [
            'code',
            'commitId',
            'userId',
            'access',
            'status',
            'created',
            'changed'
        ];
    }
}
