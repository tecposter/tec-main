<?php
namespace Tec\Article\Ui;

use Gap\Http\Response;
use Gap\Http\RedirectResponse;

use Tec\Article\Service\ArticleService;

class ArticleUi extends UiBase
{
    public function show(): Response
    {
        $articleCode = $this->getParam('articleCode');
        $detail = (new ArticleService($this->getApp()))
            ->fetchDetail($articleCode);

        return $this->view('page/article/show', [
            'detail' => $detail
        ]);
    }

    public function reqCreating(): RedirectResponse
    {
        $userId = $this->request->getSession()->get('userId');
        if (!$userId) {
            throw new \Exception('not login');
        }

        $commitCode  = (new ArticleService($this->getApp()))->reqCreating($userId);
        $updateUrl = $this->getRouteUrlBuilder()
            ->routeGet('article-update-commit', ['commitCode' => $commitCode]);
        return new RedirectResponse($updateUrl);
    }

    public function reqUpdating(): RedirectResponse
    {
    }

    public function commit(): Response
    {
    }
}
