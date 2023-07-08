<?php

##       #### ########  ########     ###    ########  ##    ##
##        ##  ##     ## ##     ##   ## ##   ##     ##  ##  ##
##        ##  ##     ## ##     ##  ##   ##  ##     ##   ####
##        ##  ########  ########  ##     ## ########     ##
##        ##  ##     ## ##   ##   ######### ##   ##      ##
##        ##  ##     ## ##    ##  ##     ## ##    ##     ##
######## #### ########  ##     ## ##     ## ##     ##    ##

namespace App;

use Olsonhost\Phat\Phat;

class Library
{

    public $domain;

    public $params, $site, $name, $arg1, $arg2, $arg3, $debug = true;

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

        $this->domain = $_SERVER['SERVER_NAME'];
        $this->site = !empty($this->params[0] ?? null) ? $this->params[0] : 'default';
        $this->name = !empty($this->params[1] ?? null) ? $this->params[1] : 'home';
        $this->arg1 = $this->params[2] ?? false;
        $this->arg2 = $this->params[3] ?? false;
        $this->arg3 = $this->params[4] ?? false;
    }


    ##     ## #### ######## ##      ##
    ##     ##  ##  ##       ##  ##  ##
    ##     ##  ##  ##       ##  ##  ##
    ##     ##  ##  ######   ##  ##  ##
     ##   ##   ##  ##       ##  ##  ##
      ## ##    ##  ##       ##  ##  ##
       ###    #### ########  ###  ###


    public function view($output, $data = []) {

        // Render the output using the Phat viewer
        $phat = new Phat;

        $output = $phat->view($output, $data);

        if ($this->debug) {
            $str_data = json_encode($data, JSON_PRETTY_PRINT);
            $uri = $_SERVER['REQUEST_URI'];
            $debug = // make this a template
                "<button class='btn btn-danger btn-sm debug-info-button' onclick=\"$('.debug-info').toggle()\">Debug</button><br/>
                <textarea class='debug-info' style='width:50%; min-width:350px; height:500px; display:none;'>
                Site: {$this->site}   Page Name: {$this->name}   Arg1: {$this->arg1}   Arg2: {$this->arg2}   Arg3: {$this->arg3}
                
                URI: $uri
                
                Data: $str_data
                </textarea>
                
                ";


            $output .= $debug;
        }

        return $output;

    }

       ###    ########   #######  ########  ########
      ## ##   ##     ## ##     ## ##     ##    ##
     ##   ##  ##     ## ##     ## ##     ##    ##
    ##     ## ########  ##     ## ########     ##
    ######### ##     ## ##     ## ##   ##      ##
    ##     ## ##     ## ##     ## ##    ##     ##
    ##     ## ########   #######  ##     ##    ##

    public function abort($code=500, $message='An error has occurred', $details = 'No details') {

        // Todo: Actually make the HTTP error header

        // Todo: Use a(n optional) template for this

        exit("
                <html>
                <body>
                <h1>$code</h1>
                <p>$message</p>
                <ul>
                    <li>Site {$this->site}</li><li>Page Name {$this->name}</li><li>Arg1 {$this->arg1}</li><li>Arg2 {$this->arg2}</li><li>Arg3 {$this->arg3}</li>
                </ul>
                <pre>$details</pre>
                </body>
                </html>
            ");
    }

}