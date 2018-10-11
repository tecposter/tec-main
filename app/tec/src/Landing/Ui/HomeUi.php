<?php
namespace Tec\Landing\Ui;

use Gap\Http\Response;

class HomeUi extends UiBase
{
    public function show(): Response
    {
        return $this->view('page/landing/home');
    }

    public function uiShow(): Response
    {
        return $this->view('page/landing/ui');
    }
}
