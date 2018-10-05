<?php
namespace Tec\Portal\Article\Ui;

use Gap\Http\Response;

class ArticleUi extends UiBase
{
    public function create(): Response
    {
        return new Response('show');
    }
}
