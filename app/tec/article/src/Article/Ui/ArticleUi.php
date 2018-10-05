<?php
namespace Tec\Article\Article\Ui;

use Gap\Http\RedirectResponse;

use Tec\Article\Article\Dto\ArticleDto;
use Tec\Article\Article\Service\ArticleService;

class ArticleUi extends UiBase
{
    public function create(): RedirectResponse
    {
        $userId = $this->request->getSession()->get('userId');
        if (!$userId) {
            throw new \Exception('not login');
        }

        $articleDto = new ArticleDto(['userId' => $userId]);
        $articleService = new ArticleService($this->app);
        $articleService->create($articleDto);

        $updateArticleCommitUrl = $this->getRouteUrlBuilder()
            ->routeGet('updateArticleCommit', ['commitId' => $articleDto->commitId]);

        return new RedirectResponse($updateArticleCommitUrl);
    }
}
