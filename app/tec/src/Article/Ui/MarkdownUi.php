<?php
namespace Tec\Article\Ui;

use Gap\Http\Response;
use Tec\Article\Service\ArticleService;
use Tec\Article\Enum\CommitStatus;

class MarkdownUi extends UiBase
{
    const ADMIN_USERID = 1;
    const TUTORIAL_CODE = '0e1bf5c034869c02';

    public function show(): Response
    {
        $commit = (new ArticleService($this->getApp()))
            ->fetchCommit(self::ADMIN_USERID, self::TUTORIAL_CODE);

        if (is_null($commit)) {
            throw new \Exception("connot find commit: tutorial code");
        }

        return $this->view('page/article/markdown', [
            'commit' => $commit
        ]);
    }
}
