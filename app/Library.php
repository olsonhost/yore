<?php

namespace App;

class Library
{

    public $params, $site, $page, $arg1, $arg2, $arg3;

    public function init() {
        $this->params = explode('/',
            strtolower(
                trim(
                    str_replace(
                        [' ','-','.'],
                        '_',
                        trim($_SERVER['REQUEST_URI'], '/')
                    )
                )
            )
        );

        $this->site = $this->params[0] ?? 'home';
        $this->page = $this->params[1] ?? 'home';
        $this->arg1 = $this->params[2] ?? false;
        $this->arg2 = $this->params[3] ?? false;
        $this->arg3 = $this->params[4] ?? false;
    }

}