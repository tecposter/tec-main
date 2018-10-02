<?php
namespace Tec\Portal\Landing\Ui;

use Gap\Http\Response;

class EditorUi extends UiBase
{
    public function monaco(): Response
    {
        return $this->view('page/landing/monaco');
    }
}
