<?php

namespace App;

use Olsonhost\Phat\Phat;

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

    public function view($output, $data = []) {

        // Render the output using the Phat viewer
        $phat = new Phat;

        echo $phat->view($output, $data);

        echo $output;

        return true;
    }

    public function abort($code=500, $message='An error has occurred', $details = 'No details') {

        // Todo: Actually make the HTTP error header

        // Todo: Use a(n optional) template for this

        exit("
                <html>
                <body>
                <h1>$code</h1>
                <p>$message</p>
                <ul>
                    <li>Site {$this->site}</li><li>Page {$this->page}</li><li>Arg1 {$this->arg1}</li><li>Arg2 {$this->arg2}</li><li>Arg3 {$this->arg3}</li>
                </ul>
                <pre>$details</pre>
                </body>
                </html>
            ");
    }

}