<?php

namespace App\View;

class BaseView
{
    public function render($page, $data)
    {
        include $_SERVER['DOCUMENT_ROOT']. "/courier". $page;
    }
}
