<?php
namespace Tec\Article\Repo;

use Tec\Article\Dto\DetailDto;
use Tec\Article\Dto\CommitDto;

use Tec\Article\Enum\ArticleStatus;
use Tec\Article\Enum\ArticleAccess;
use Tec\Article\Enum\CommitStatus;

use Gap\Dto\DateTime;
use Gap\Dto\TimeUniq;

class ArticleRepo extends RepoBase
{
    const ARTICLE_TABLE = 'tec_article';
    const COMMIT_TABLE = 'tec_article_commit';
    const USER_TABLE = 'tec_user';

    //const ARTICLE_STATUS_DEFAULT = 'normal';
    //const COMMIT_STATUS_DEFAULT = 'draft';
    //const ACCESS_DEFAULT = 'private';

    public function fetchDetail(string $slug): ?DetailDto
    {
        if (empty($slug)) {
            throw new \Exception('slug cannot be empty');
        }

        $articleTable = self::ARTICLE_TABLE;
        $commitTable = self::COMMIT_TABLE;
        $userTable = self::USER_TABLE;

        return $this->cnn->ssb()
            ->select(
                'a.slug slug',
                'u.slug userSlug',
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
                    ->andExpect('a.commitId')->equal()->expr('c.commitId')
                ->endJoin()
                ->leftJoin("$userTable u")
                ->onCond()
                    ->expect('a.userId')->equal()->expr('u.userId')
                ->endJoin()
            ->end()
            ->where()
                ->expect('a.slug')->equal()->str($slug)
            ->end()
            ->fetch(DetailDto::class);
    }

    public function fetchCommit(int $userId, string $codeHex): ?CommitDto
    {
        $code = hex2bin($codeHex);
        return $this->getCommitSsb(
            'c.code',
            'a.slug',
            'c.content',
            'c.status',
            'c.created',
            'c.changed'
        )->where()
            ->expect('c.code')->equal()->str($code)
            ->andExpect('c.userId')->equal()->int($userId)
        ->end()
        ->fetch(CommitDto::class);
    }

    public function saveCommitContent(int $userId, string $codeHex, string $inContent): void
    {
        $commitTable = self::COMMIT_TABLE;
        $code = hex2bin(trim($codeHex));
        $content = trim($inContent);
        if (empty($code)) {
            throw new \Exception('code cannot be empty');
        }
        if (empty($content)) {
            throw new \Exception('content cannot be empty');
        }
        if ($userId <= 0) {
            throw new \Exception('userId cannot be less than or equal 0');
        }

        $this->cnn->usb()
            ->update($commitTable)->end()
            ->set('content')->str($content)
            ->set('changed')->dateTime(new DateTime())
            ->where()
                ->expect('code')->equal()->str($code)
                ->andExpect('userId')->equal()->int($userId)
            ->end()
            ->execute();
    }

    public function publish(int $userId, string $codeHex, string $inSlug, string $access): void
    {
        $articleTable = self::ARTICLE_TABLE;
        $commitTable = self::COMMIT_TABLE;

        $commitCode = hex2bin(trim($codeHex));
        $slug = trim($inSlug);

        if (empty($commitCode)) {
            throw new \Exception('commit code cannot be empty');
        }
        if (empty($slug)) {
            throw new \Exception('slug cannot be empty');
        }
        $this->assertSlugNotExists($commitCode, $slug);
        $this->assertDraftCommit($commitCode);

        $now = new DateTime();
        $this->cnn->usb()
            ->update("$articleTable a")
                ->leftJoin("$commitTable c")
                ->onCond()
                    ->expect('a.articleId')->equal()->expr('c.articleId')
                ->endJoin()
            ->end()
            ->set('a.slug')->str($slug)
            ->set('a.access')->str($access)
            ->set('a.commitId')->expr('c.commitId')
            ->set('c.status')->str(CommitStatus::PUBLISHED)
            ->set('a.changed')->dateTime($now)
            ->set('c.changed')->dateTime($now)
            ->where()
                ->expect('c.code')->equal()->str($commitCode)
                ->andExpect('c.status')->equal()->str(CommitStatus::DRAFT)
                ->andExpect('a.userId')->equal()->int($userId)
            ->end()
            ->execute();
    }

    public function reqUpdating(int $userId, string $inSlug): string
    {
        if ($userId <= 0) {
            throw new \Exception('error userId');
        }
        $slug = trim($inSlug);
        if (empty($slug)) {
            throw new \Exception('slug cannot be empty');
        }

        $draftCommit = $this->getCommitSsb('c.code')
            ->where()
                ->expect('a.slug')->equal()->str($slug)
                ->andExpect('c.status')->equal()->str(CommitStatus::DRAFT)
            ->end()
            ->fetchAssoc();
        if ($draftCommit) {
            return $draftCommit['code'];
        }

        $currentCommit = $this->getCommitSsb('a.articleId', 'c.content')
            ->where()
                ->expect('a.slug')->equal()->str($slug)
                ->andExpect('a.commitId')->equal()->expr('c.commitId')
            ->end()
            ->fetchAssoc();
        if (is_null($currentCommit)) {
            throw new \Exception('cannot find slug');
        }

        $newCode = $this->randomBin();
        $now = new DateTime();

        $this->cnn->isb()
            ->insert(self::COMMIT_TABLE)
            ->field(...$this->getCommitFields())
            ->value()
                ->addInt($userId)
                ->addInt($currentCommit['articleId'])
                ->addStr($newCode)
                ->addStr($currentCommit['content'])
                ->addStr(CommitStatus::DRAFT)
                ->addDateTime($now)
                ->addDateTime($now)
            ->end()
            ->execute();

        return bin2hex($newCode);
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
            $slug = bin2hex($this->randomBin());
            $emptyCommitId = 0;
            $articleAccess = ArticleAccess::DEFAULT;
            $articleStatus = ArticleStatus::DEFAULT;
            $now = new DateTime();

            $this->cnn->isb()
                ->insert($articleTable)
                ->field(...$this->getArticleFields())
                ->value()
                    ->addStr($slug)
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
            $code = $this->randomBin();
            $content = '';
            $commitStatus = CommitStatus::DEFAULT;
            $this->cnn->isb()
                ->insert($commitTable)
                ->field(...$this->getCommitFields())
                ->value()
                    ->addInt($userId)
                    ->addInt($articleId)
                    ->addStr($code)
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
        return bin2hex($code);
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
            'slug',
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

    private function assertSlugNotExists(string $commitCode, string $slug): void
    {
        $articleTable = self::ARTICLE_TABLE;
        $commitTable = self::COMMIT_TABLE;

        $existedArticle = $this->cnn->ssb()
            ->select('articleId')
            ->from($articleTable)->end()
            ->where()
                ->expect('slug')->equal()->str($slug)
            ->end()
            ->fetchAssoc();

        if (empty($existedArticle)) {
            return;
        }
        $commit = $this->cnn->ssb()
            ->select('articleId')
            ->from($commitTable)->end()
            ->where()
                ->expect('code')->equal()->str($commitCode)
            ->end()
            ->fetchAssoc();

        if ($commit && $commit['articleId'] === $existedArticle['articleId']) {
            return;
        }
        throw new \Exception('slug: ' . $slug . ' already exists');
        /*
        $existed = $this->cnn->ssb()
            ->select("a.slug")
            ->from("$articleTable a")
                ->leftJoin("$commitTable c")
                ->onCond()
                    ->expect('a.articleId')->equal()->expr('c.articleId')
                ->endJoin()
            ->end()
            ->where()
                ->expect('a.slug')->equal()->str($slug)
                ->andExpect('c.code')->notEqual()->str($commitCode)
            ->end()
            ->fetchAssoc();
        if ($existed) {
            throw new \Exception('slug: ' . $slug . ' already exists');
        }
         */
    }

    private function assertDraftCommit(string $commitCode): void
    {
        $existed = $this->cnn->ssb()
            ->select('status')
            ->from(self::COMMIT_TABLE)->end()
            ->where()
                ->expect('code')->equal()->str($commitCode)
            ->end()
            ->fetchAssoc();
        if (!$existed) {
            throw new \Exception('Cannot find commit');
        }

        if ($existed['status'] !== CommitStatus::DRAFT) {
            throw new \Exception('Commit must be draft');
        }
    }

    private function getCommitSsb(string ...$fields)
    {
        $articleTable = self::ARTICLE_TABLE;
        $commitTable = self::COMMIT_TABLE;
        return $this->cnn->ssb()
            ->select(...$fields)
            ->from("{$commitTable} c")
                ->leftJoin("$articleTable a")
                ->onCond()
                    ->expect('c.articleId')->equal()->expr('a.articleId')
                ->endJoin()
            ->end();
    }
}
