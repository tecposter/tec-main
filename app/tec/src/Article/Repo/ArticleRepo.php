<?php
namespace Tec\Article\Repo;

use Tec\Article\Dto\DetailDto;
use Tec\Article\Dto\CommitDto;

use Gap\Dto\DateTime;
use Gap\Dto\TimeUniq;

class ArticleRepo extends RepoBase
{
    const ARTICLE_TABLE = 'tec_article';
    const COMMIT_TABLE = 'tec_article_commit';
    const USER_TABLE = 'tec_user';

    const ARTICLE_STATUS_DEFAULT = 'normal';
    const COMMIT_STATUS_DEFAULT = 'draft';
    const ACCESS_DEFAULT = 'private';

    public function fetchDetail(string $code): ?DetailDto
    {
        if (empty($code)) {
            throw new \Exception('code cannot be empty');
        }

        $articleTable = self::ARTICLE_TABLE;
        $commitTable = self::COMMIT_TABLE;
        $userTable = self::USER_TABLE;

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

    public function reqCreating(int $userId): string
    {
        if ($userId <= 0) {
            throw new \Exception('error userId');
        }

        $trans = $this->cnn->trans();
        $trans->begin();
        try {
            $articleTable = self::ARTICLE_TABLE;
            $articleCode = bin2hex($this->randomBin());
            $emptyCommitId = 0;
            $articleAccess = self::ACCESS_DEFAULT;
            $articleStatus = self::ARTICLE_STATUS_DEFAULT;
            $now = new DateTime();

            $this->cnn->isb()
                ->insert($articleTable)
                ->field(...$this->getArticleFields())
                ->value()
                    ->addStr($articleCode)
                    ->addInt($emptyCommitId)
                    ->addInt($userId)
                    ->addStr($articleAccess)
                    ->addStr($articleStatus)
                    ->addDateTime($now)
                    ->addDateTime($now)
                ->end()
                ->execute();
            $articleId = $this->cnn->lastInsertId();

            $commitTable = self::COMMIT_TABLE;
            $commitCode = $this->randomBin();
            $content = '';
            $commitStatus = self::COMMIT_STATUS_DEFAULT;
            $this->cnn->isb()
                ->insert($commitTable)
                ->field(...$this->getCommitFields())
                ->value()
                    ->addInt($userId)
                    ->addInt($articleId)
                    ->addStr($commitCode)
                    ->addStr($content)
                    ->addStr($commitStatus)
                    ->addDateTime($now)
                    ->addDateTime($now)
                ->end()
                ->execute();

            //$commit = (new CommitRepo($this->dmg))->create($userId, $articleId);
        } catch (\Exception $e) {
            $trans->rollback();
            throw $e;
        }
        $trans->commit();
        return bin2hex($commitCode);
    }

    private function randomBin(): string
    {
        $timeHex = dechex(microtime(true) * 10 ** 4);
        $paded = str_pad($timeHex, 12, '0', STR_PAD_LEFT);
        return hex2bin($paded) . random_bytes(2);
    }

    private function getArticleFields(): array
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

    private function getCommitFields(): array
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
