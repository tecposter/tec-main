<?php
namespace Tec\Article\Article\Open;

use Gap\Http\JsonResponse;
use Tec\Article\Article\Service\ArticleService;

class ArticleOpen extends OpenBase
{
    public function fetchById(): JsonResponse
    {
        $articleId = $this->request->request->get('articleId');
        $articleDto = (new ArticleService($this->app))->fetchById($articleId);

        return new JsonResponse($articleDto);
    }
}
