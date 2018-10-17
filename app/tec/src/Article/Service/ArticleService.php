<?php
namespace Tec\Article\Service;

use Tec\Article\Dto\ArticleDetailDto;
use Tec\Article\Repo\ArticleRepo;

class ArticleService extends ServiceBase
{
    public function fetchDetailByCode(string $code): ?ArticleDetailDto
    {
        return (new ArticleRepo($this->getDmg()))->fetchDetailByCode($code);
    }
}
