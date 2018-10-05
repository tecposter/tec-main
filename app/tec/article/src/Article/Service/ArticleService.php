<?php
namespace Tec\Article\Article\Service;

use Tec\Article\Article\Dto\ArticleDto;
use Tec\Article\Article\Repo\ArticleRepo;

class ArticleService extends ServiceBase
{
    public function create(ArticleDto $articleDto): void
    {
        $articleRepo = new ArticleRepo($this->getDmg());
        $articleRepo->create($articleDto);
    }
}
