<?php
namespace Tec\Article\Article\Repo;

use Gap\Dto\DateTime;

use Tec\Article\Article\Dto\ArticleDto;
use Tec\Article\Article\Dto\CommitDto;

class ArticleRepo extends RepoBase
{
    private $table = 'tec_article';
    //private $commitTable = 'tec_article_commit';

    private $statusDefault = 'draft';
    private $accessDefault = 'private';
    private $zcodeByteLen = 16;

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
