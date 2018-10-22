<?php
namespace Tec\Article\Ui;

use Gap\Http\Response;

use Tec\Article\Service\CommitService;

class CommitUi extends UiBase
{
    public function update(): Response
    {
        $code = $this->getParam('code');
        $commit = (new CommitService($this->getApp()))->fetchByCode($code);
        if (is_null($commit)) {
            throw new \Exception('cannot find commit');
        }
        if ($commit->status === 'published') {
            throw new \Exception('The commit has already be published');
        }

        return $this->view('page/article/commit-update', [
            'commit' => $commit
        ]);
    }
}
