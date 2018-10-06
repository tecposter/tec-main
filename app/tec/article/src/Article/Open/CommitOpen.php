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
        $accessToken = $this->request->attributes->get('accessToken');
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
}
