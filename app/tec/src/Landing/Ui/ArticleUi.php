<?php
namespace Tec\Landing\Ui;

use Gap\Http\Response;
use Tec\Landing\Service\ArticleService;

const ITEM_PER_PAGE = 20;

class ArticleUi extends UiBase
{
    public function draft(): Response
    {
        $currentUserId = $this->getCurrentUserId();

        $draftList = $this->getArticleService()->listDraft($currentUserId);

        $page = $this->request->query->get('page', 1);
        $limit = ITEM_PER_PAGE;
        $offset = intval(($page - 1) * $limit);


        $draftList->limit($limit);
        $draftList->offset($offset);

        return $this->view('page/landing/article-draft', [
            'draftList' => $draftList,
            'page' => $page,
            'itemPerPage' => $limit
        ]);
    }

    private function getArticleService(): ArticleService
    {
        return new ArticleService($this->getApp());
    }

    private function getCurrentUserId(): int
    {
        $session = $this->request->getSession();
        $userId = intval($session->get('userId'));
        if ($userId > 0) {
            return intVal($userId);
        }

        throw new \Exception('not login');
    }
}
