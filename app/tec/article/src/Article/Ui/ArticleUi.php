<?php
namespace Tec\Article\Article\Ui;

use Gap\Http\RedirectResponse;
use Gap\Http\Response;

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

    public function show(): Response
    {
        $zcode = $this->getParam('zcode');
        $articleDetailDto = (new ArticleService($this->app))->fetchDetailByZcode($zcode);

        return $this->view('page/article/show', [
            'articleDetailDto' => $articleDetailDto
        ]);
        //return new Response('show: ' . json_encode($articleDetailDto));
    }
}
