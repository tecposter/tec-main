<?php
namespace Tec\Landing\Ui;

use Gap\Http\Response;

use Tec\Landing\Service\ArticleService;

class HomeUi extends UiBase
{
    public function show(): Response
    {
        $articleList = $this->getArticleService()->list();
        return $this->view('page/landing/home', [
            'articleList' => $articleList
        ]);
    }

    public function uiShow(): Response
    {
        return $this->view('page/landing/ui');
    }

    private function getArticleService(): ArticleService
    {
        return new ArticleService($this->getApp());
    }
}
