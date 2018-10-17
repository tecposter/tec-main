<?php
namespace Tec\Article\Ui;

use Gap\Http\Response;
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

    public function create(): Response
    {
        return new Response('show');
    }
}
