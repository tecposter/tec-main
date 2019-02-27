<?php
namespace Tec\Landing\Ui;

use Gap\Http\Response;

use Tec\Landing\Service\ArticleService;

const ITEM_PER_PAGE = 20;

class HomeUi extends UiBase
{
    public function show(): Response
    {
        $articleList = $this->getArticleService()->list();

        $page = $this->request->query->get('page', 1);
        $limit = ITEM_PER_PAGE;
        $offset = intval(($page - 1) * $limit);


        $articleList->limit($limit);
        $articleList->offset($offset);

        return $this->view('page/landing/home', [
            'articleList' => $articleList,
            'page' => $page,
            'itemPerPage' => $limit
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
