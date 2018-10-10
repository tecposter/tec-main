<?php
namespace Tec\Article\Article\Ui;

use Gap\Http\Response;

use Tec\Article\Article\Service\CommitService;

class CommitUi extends UiBase
{
    public function update(): Response
    {
        $commitId = $this->getParam('commitId');
        $commitDto = (new CommitService($this->app))->fetchById($commitId);
        if (is_null($commitDto)) {
            throw new \Exception('cannot find commit');
        }

        if ($commitDto->status === 'published') {
            throw new \Exception('The commit has already be published');
        }

        return $this->view('page/article/commit-update', [
            'commitDto' => $commitDto
        ]);
    }
}
