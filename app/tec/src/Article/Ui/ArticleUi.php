<?php
namespace Tec\Article\Ui;

use Gap\Http\Response;
use Gap\Http\RedirectResponse;

use Tec\Article\Service\ArticleService;

class ArticleUi extends UiBase
{
    public function show(): Response
    {
        $code = $this->getParam('code');
        $articleDetail = (new ArticleService($this->getApp()))
            ->fetchDetailByCode($code);

        return $this->view('page/article/show', [
            'articleDetail' => $articleDetail
        ]);
    }

    public function create(): RedirectResponse
    {
        $userId = $this->request->getSession()->get('userId');
        if (!$userId) {
            throw new \Exception('not login');
        }

        $commit  = (new ArticleService($this->getApp()))->createCommit($userId);
        $updateCommitUrl = $this->getRouteUrlBuilder()
            ->routeGet('article-commit-update', ['code' => $commit->code]);
        return new RedirectResponse($updateCommitUrl);
    }
}
