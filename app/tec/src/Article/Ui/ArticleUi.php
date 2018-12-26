<?php
namespace Tec\Article\Ui;

use Gap\Http\Response;
use Gap\Http\RedirectResponse;

use Tec\Article\Service\ArticleService;
use Tec\Article\Dto\CommitDto;
use Tec\Article\Enum\CommitStatus;

class ArticleUi extends UiBase
{
    public function show(): Response
    {
        $slug = $this->getParam('slug');
        $detail = (new ArticleService($this->getApp()))
            ->fetchDetail($slug);

        return $this->view('page/article/show', [
            'detail' => $detail
        ]);
    }

    public function reqCreating(): RedirectResponse
    {
        $userId = $this->getCurrentUserId();
        $code  = (new ArticleService($this->getApp()))->reqCreating($userId);
        $updateUrl = $this->getRouteUrlBuilder()
            ->routeGet('article-update-commit', ['code' => $code]);
        return new RedirectResponse($updateUrl);
    }

    public function reqUpdating(): RedirectResponse
    {
    }

    public function updateCommit(): Response
    {
        $currentUserId = $this->getCurrentUserId();
        $code = $this->getParam('code');
        $commit = (new ArticleService($this->getApp()))
            ->fetchCommit($currentUserId, $code);

        if (is_null($commit)) {
            throw new \Exception("connot find commit: $code");
        }

        if ($commit->status === CommitStatus::PUBLISHED) {
            throw new \Exception("This commit[$code] has already been published");
        }

        return $this->view('page/article/update-commit', [
            'commit' => $commit
        ]);
    }

    private function getCurrentUserId(): int
    {
        $userId = $this->request->getSession()->get('userId');
        $userId = intval($userId);
        if ($userId <= 0) {
            throw new \Exception('not login');
        }
        return $userId;
    }
}
