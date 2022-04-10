<?php

namespace App\Widgets;

class LoginWidget
{
    public function __invoke($params)
    {
        return view('site.widgets.login');
    }
}
