<?php

namespace App;

class Controller {

    public $params, $site, $page, $arg1, $arg2, $arg3;

    public $data;

    public function __construct() {

        $this->params = explode('/',
            strtolower(
                trim(
                    str_replace(
                        [' ','-','.'],
                        '_',
                        $_SERVER['REQUEST_URI']
                    )
                )
            )
        );

        $this->site = $params[0] ?? 'home';
        $this->page = $params[1] ?? 'home';
        $this->arg1 = $params[2] ?? '';
        $this->arg2 = $params[3] ?? '';
        $this->arg3 = $params[4] ?? '';

    }

    public function page() {

        $this->data = $this->getData();

    }

    public function getData() {

        # If the page exists, return the json data for the page

        # otherwise return a blank template

        if (!file_get_contents('../pages/' . $this->site . '/' . $this->page . '.json')) {
            // get pages/default/home.json
            // update the json to populate the site and page being asked for and return it
            // (so that it may be edited and saved by the user)
            // (or edited but not saved by a guest)
            // (guests can edit new and existing pages but only users can save them)
        }

    }

}
