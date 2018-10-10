<?php
namespace Tec\Article\Article\Open;

use Gap\Http\JsonResponse;
use Tec\Article\Article\Dto\CommitDto;
use Tec\Article\Article\Service\CommitService;

class CommitOpen extends OpenBase
{
    public function update(): JsonResponse
    {
        $post = $this->request->request;
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            throw new \Exception('cannot get accessToken');
        }

        $commitDto = new CommitDto([
            'articleId' => $post->get('articleId'),
            'commitId' => $post->get('commitId'),
            'userId' => $post->get('userId'),
            'status' => $post->get('status'),
            'content' => $post->get('content'),
            'created' => $post->get('created'),
            'changed' => $post->get('changed'),
        ]);

        if ($accessToken->userId !== $commitDto->userId) {
            throw new \Exception('userId not match');
        }

        (new CommitService($this->app))->update($commitDto);

        return new JsonResponse($commitDto);
    }

    public function publish(): JsonResponse
    {
        $post = $this->request->request;
        $articleId = $post->get('articleId');
        $zcode = $post->get('zcode');
        $commitId = $post->get('commitId');
        $isPublic = $post->get('isPublic');

        $access = ($isPublic === 'true') ? 'public' : 'private';

        $accessToken = $this->getAccessToken();

        (new CommitService($this->app))->publish(
            $accessToken->userId,
            $articleId,
            $commitId,
            $zcode,
            $access
        );

        return new JsonResponse([
            'url' => $this->getRouteUrlBuilder()->routeGet('article-show', ['zcode' => $zcode])
        ]);
    }
}
