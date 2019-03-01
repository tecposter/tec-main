<?php
namespace Tec\Article\Open;

use Gap\Http\JsonResponse;
use Tec\Article\Service\ArticleService;
use Tec\Article\Enum\ArticleAccess;

class ArticleOpen extends OpenBase
{
    public function saveCommitContent(): JsonResponse
    {
        $post = $this->request->request;
        $userId = $this->getUserId();
        $code = $post->get('code');
        $content = $post->get('content');

        $this->getArticleService()->saveCommitContent($userId, $code, $content);
        return new JsonResponse([
            'code' => $code,
            'content' => $content
        ]);
    }

    public function fetchReleasedContent(): JsonResponse
    {
        $slug = $this->request->request->get('slug');
        if (empty($slug)) {
            throw new \Exception('slug cannot be empty');
        }
        $detail = $this->getArticleService()->fetchDetail($slug);
        if (!$detail) {
            throw new \Exception('cannot fetch article');
        }
        return new JsonResponse([
            'content' => $detail->content
        ]);
    }

    public function publish(): JsonResponse
    {
        $userId = $this->getUserId();

        $post = $this->request->request;
        $code = $post->get('code');
        $slug = $post->get('slug');

        $access = $post->get('isPublic') === "true" ? ArticleAccess::PUBLIC : ArticleAccess::PRIVATE;
        $this->getArticleService()->publish($userId, $code, $slug, $access);

        return new JsonResponse([
            'code' => $code,
            'slug' => $slug,
            'access' => $access
        ]);
    }

    private function getArticleService(): ArticleService
    {
        return new ArticleService($this->app);
    }

    private function getUserId()
    {
        $accessToken = $this->request->attributes->get('accessToken');
        $userId = (int) $accessToken->userId;
        return $userId;
    }
}
