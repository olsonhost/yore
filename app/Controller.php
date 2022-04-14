<?php

namespace App;

class Controller extends Library {

    public $data, $json;

    public $header, $footer, $html, $js = [], $css = [], $output, $page;

    public function __construct() {

        $this->init();

        //var_dump([$this->site,$this->page,$this->arg1,$this->arg2,$this->arg3]);
        // https://yoreweb.com/ho/ha/this/that/tother
        // array(5) { [0]=> string(2) "ho" [1]=> string(2) "ha" [2]=> string(4) "this" [3]=> string(4) "that" [4]=> string(6) "tother" }

        $ok = $this->page();

        $ok = $this->process();

        $ok = $this->assemble();

        $this->page = $this->view($this->output, $this->data);

    }

    public function process() {

        $this->html = $this->data->body;

    }

    public function assemble() {

        $dir = __DIR__ . '/../web/themes/' . $this->data->theme . '/css/*.css';

        foreach (glob($dir) as $filename) {
            $this->css[] = explode('/../web', $filename)[1];
        }

        if (empty($this->css)) {

            $this->abort(500, "Missing Stylesheet");

        }

        $dir = __DIR__ . '/../web/themes/' . $this->data->theme . '/js/*.js';

        foreach (glob($dir) as $filename) {
            $this->js[] = explode('/../web', $filename)[1];
        }

        if (empty($this->js)) {

            $this->abort(500, "Missing Javascript");

        }

        // The theme header and footer must include all tags necessary to enclose the body including start and end body tags

        $header_file = __DIR__ . '/../web/themes/' . $this->data->theme . '/html/header.php';
        ob_start();
        //$return =
            include $header_file; // Can return a value with return()
        $this->output .= ob_get_clean();

        $this->output .= $this->cssFiles();

        $this->output .= $this->html;

        $this->output .= $this->jsFiles();

        $footer_file = __DIR__ . '/../web/themes/' . $this->data->theme . '/html/footer.php';
        ob_start();
        //$return =
        include $footer_file; // Can return a value with return()
        $this->output .= ob_get_clean();

        return true;

    }

    public function cssFiles() {

        $output = '';

        foreach ($this->css as $file) {

            $output .= "<link media=\"all\" rel=\"stylesheet\" href=\"$file\" />\n";

        }
        return $output;
    }

    public function jsFiles() {

        $output = '';

        foreach ($this->js as $file) {

            $output .= "<script defer=\"defer\"  type=\"application/javascript\" src=\"$file\" /></script>\n";

        }
        return $output;
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
