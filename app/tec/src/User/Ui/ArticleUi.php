<?php
namespace Tec\User\Ui;

use Gap\Http\Response;
use Tec\User\Service\ArticleService;

class ArticleUi extends UiBase
{
    public function draft(): Response
    {
        $currentUserId = $this->getCurrentUserId();

        $draftList = $this->getArticleService()->listDraft($currentUserId);
        return $this->view('page/user/article-draft', [
            'draftList' => $draftList
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
