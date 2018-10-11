<?php
namespace Tec\Article\Article\Repo;

use Gap\Dto\DateTime;

use Tec\Article\Article\Dto\ArticleDto;
use Tec\Article\Article\Dto\ArticleDetailDto;
use Tec\Article\Article\Dto\CommitDto;

class ArticleRepo extends RepoBase
{
    private $table = 'tec_article';
    //private $commitTable = 'tec_article_commit';

    private $statusDefault = 'draft';
    private $accessDefault = 'private';
    private $zcodeByteLen = 16;

    public function getTable(): string
    {
        return $this->table;
    }

    public function create(ArticleDto $articleDto): void
    {
        if (!$articleDto->userId) {
            throw new \Exception('cannot find userId in articleDto');
        }

        $articleDto->articleId = $this->cnn->zid();
        $articleDto->zcode = bin2hex(random_bytes($this->zcodeByteLen));

        $now = new DateTime();
        $articleDto->created = $now;
        $articleDto->changed = $now;

        if (!$articleDto->status) {
            $articleDto->status = $this->statusDefault;
        }

        if (!$articleDto->access) {
            $articleDto->access = $this->accessDefault;
        }

        $trans = $this->cnn->trans();
        $trans->begin();
        try {
            $commitDto = new CommitDto([
                'userId' => $articleDto->userId,
                'articleId' => $articleDto->articleId
            ]);
            $commitRepo = new CommitRepo($this->dmg);
            $commitRepo->create($commitDto);
            $articleDto->commitId = $commitDto->commitId;

            $this->cnn->isb()
                ->insert($this->table)
                ->field(
                    ...$this->getFields()
                )
                ->value()
                    ->addStr($articleDto->articleId)
                    ->addStr($articleDto->zcode)
                    ->addStr($articleDto->commitId)
                    ->addStr($articleDto->userId)
                    ->addStr($articleDto->access)
                    ->addStr($articleDto->status)
                    ->addDateTime($articleDto->created)
                    ->addDateTime($articleDto->changed)
                ->end()
                ->execute();
        } catch (\Exception $e) {
            $trans->rollback();
            throw $e;
        }

        $trans->commit();
    }

    public function fetchById(string $articleId): ?ArticleDto
    {
        if (empty($articleId)) {
            return null;
        }

        return $this->cnn->ssb()
            ->select(
                ...$this->getFields()
            )
            ->from($this->table)->end()
            ->where()
                ->expect('articleId')->equal()->str($articleId)
            ->end()
            ->fetch(ArticleDto::class);
    }

    public function fetchByZcode(string $zcode): ?ArticleDto
    {
        if (empty($zcode)) {
            return null;
        }

        $ssb = $this->getSsb();
        $ssb->where()->expect('zcode')->equal()->str($zcode);
        return $ssb->fetch(ArticleDto::class);
    }

    public function fetchDetailByZcode(string $zcode): ?ArticleDetailDto
    {
        if (empty($zcode)) {
            return null;
        }

        $userTable = 'tec_user';
        $commitTable = 'tec_article_commit';

        return $this->cnn->ssb()
            ->select(
                'a.articleId',
                'a.zcode',
                'a.commitId',
                'a.userId',
                'u.zcode userZcode',
                'u.fullname userFullname',
                'c.content',
                'a.access',
                'a.status',
                'a.created',
                'a.changed'
            )
            ->from("{$this->table} a")
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
                ->expect('a.zcode')->equal()->str($zcode)
            ->end()
            ->fetch(ArticleDetailDto::class);
    }

    public function assertNotDuplicated(string $articleId, string $field, $val): void
    {
        $existed = $this->cnn->ssb()
            ->select($field)
            ->from($this->table)->end()
            ->where()
                ->expect($field)->equal()->str($val)
                ->andExpect('articleId')->notEqual()->str($articleId)
            ->end()
            ->fetchAssoc();

        if ($existed) {
            throw new \Exception($field . ': ' . $val . ' already exists in ' . $this->table);
        }
    }

    private function getSsb()
    {
        $ssb = $this->cnn->ssb();
        $ssb->select(...$this->getFields());
        $ssb->from($this->table);
        return $ssb;
    }

    private function getFields()
    {
        return [
            'articleId',
            'zcode',
            'commitId',
            'userId',
            'access',
            'status',
            'created',
            'changed'
        ];
    }
}
