<?php

namespace App;

class Controller extends Library {

    public $data, $json;

    public $header, $footer, $html, $js, $css, $output;

    public $assets; // array of css and js links to be included in output

    public function __construct() {

        $this->init();

        //var_dump([$this->site,$this->page,$this->arg1,$this->arg2,$this->arg3]);
        // https://yoreweb.com/ho/ha/this/that/tother
        // array(5) { [0]=> string(2) "ho" [1]=> string(2) "ha" [2]=> string(4) "this" [3]=> string(4) "that" [4]=> string(6) "tother" }

        $ok = $this->page();

        $ok = $this->process();

        $ok = $this->assemble();

        exit($this->output);

    }

    public function process() {

        $this->html = $this->data->body;

    }

    public function assemble() {

        // In the helper class or parent thing, make methods to doing this repetetive task

        $fi = __DIR__ . '/../web/themes/' . $this->data->theme . '/css/theme.css';  // Why not make this Php ?

        //exit($fi);

        $this->css = file_get_contents($fi);

       // var_dump($this->css); exit;

        if ($this->css === false) {

            // Make this a template or something

            exit("
                <html>
                <body>
                <h1>500</h1>
                <p>Missing CSS</p>
                <ul>
                    <li>Site {$this->site}</li><li>Page {$this->page}</li><li>Arg1 {$this->arg1}</li><li>Arg2 {$this->arg2}</li><li>Arg3 {$this->arg3}</li>
                </ul>
                <pre>{$this->json}</pre>
                </body>
                </html>
           
            ");
        }
        // get any additional css files in here
        // foreach blah blah make include files

        $fi = __DIR__ . '/../web/themes/' . $this->data->theme . '/js/theme.js'; // Why not make this Php ?

        $this->js = file_get_contents($fi);

        if ($this->js === false) {
            exit("
                <html>
                <body>
                <h1>500</h1>
                <p>Missing Javascript</p>
                <ul>
                    <li>Site {$this->site}</li><li>Page {$this->page}</li><li>Arg1 {$this->arg1}</li><li>Arg2 {$this->arg2}</li><li>Arg3 {$this->arg3}</li>
                </ul>
                <pre>{$this->json}</pre>
                </body>
                </html>
           
            ");
        }
        // get any additional js files in here
        // foreach blah blah make include files

        $header_file = __DIR__ . '/../web/themes/' . $this->data->theme . '/html/header.php';
        $footer_file = __DIR__ . '/../web/themes/' . $this->data->theme . '/html/footer.php';

        // see https://stackoverflow.com/questions/851367/how-to-execute-and-get-content-of-a-php-file-in-a-variable

        // So, assemble $this->output by executing header, footer

        // then add header + include files + js + css + body + footer and put it into output

        // then output the output


    }

    public function page() {

        $this->json = $this->getData();

        $this->data = json_decode($this->json);

        return true;

    }

    public function getData() {

        # If the page exists, return the json data for the page

        # otherwise return a blank template

        $page = file_get_contents('../pages/' . $this->site . '/' . $this->page . '.json');

        if (!$page) {


            // get pages/default/home.json
            // update the json to populate the site and page being asked for and return it
            // (so that it may be edited and saved by the user)
            // (or edited but not saved by a guest)
            // (guests can edit new and existing pages but only users can save them)


            exit("
                <html>
                <body>
                <h1>404</h1>
                <p>Page not found</p>
                <ul>
                    <li>Site {$this->site}</li><li>Page {$this->page}</li><li>Arg1 {$this->arg1}</li><li>Arg2 {$this->arg2}</li><li>Arg3 {$this->arg3}</li>
                </ul>
                </body>
                </html>
            
            ");

        } else {

            return($page);

        }

    }

}
