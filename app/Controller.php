<?php

 ######   #######  ##    ## ######## ########   #######  ##       ##       ######## ########
##    ## ##     ## ###   ##    ##    ##     ## ##     ## ##       ##       ##       ##     ##
##       ##     ## ####  ##    ##    ##     ## ##     ## ##       ##       ##       ##     ##
##       ##     ## ## ## ##    ##    ########  ##     ## ##       ##       ######   ########
##       ##     ## ##  ####    ##    ##   ##   ##     ## ##       ##       ##       ##   ##
##    ## ##     ## ##   ###    ##    ##    ##  ##     ## ##       ##       ##       ##    ##
 ######   #######  ##    ##    ##    ##     ##  #######  ######## ######## ######## ##     ##

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


    ########  ########   #######   ######  ########  ######   ######
    ##     ## ##     ## ##     ## ##    ## ##       ##    ## ##    ##
    ##     ## ##     ## ##     ## ##       ##       ##       ##
    ########  ########  ##     ## ##       ######    ######   ######
    ##        ##   ##   ##     ## ##       ##             ##       ##
    ##        ##    ##  ##     ## ##    ## ##       ##    ## ##    ##
    ##        ##     ##  #######   ######  ########  ######   ######


    public function process() {

        $this->html = $this->data->body;

    }

       ###     ######   ######  ######## ##     ## ########  ##       ########
      ## ##   ##    ## ##    ## ##       ###   ### ##     ## ##       ##
     ##   ##  ##       ##       ##       #### #### ##     ## ##       ##
    ##     ##  ######   ######  ######   ## ### ## ########  ##       ######
    #########       ##       ## ##       ##     ## ##     ## ##       ##
    ##     ## ##    ## ##    ## ##       ##     ## ##     ## ##       ##
    ##     ##  ######   ######  ######## ##     ## ########  ######## ########

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


        ##     ## ########    ###    ########  ######## ########
        ##     ## ##         ## ##   ##     ## ##       ##     ##
        ##     ## ##        ##   ##  ##     ## ##       ##     ##
        ######### ######   ##     ## ##     ## ######   ########
        ##     ## ##       ######### ##     ## ##       ##   ##
        ##     ## ##       ##     ## ##     ## ##       ##    ##
        ##     ## ######## ##     ## ########  ######## ##     ##


        $header_file = __DIR__ . '/../web/themes/' . $this->data->theme . '/html/header.php';
        ob_start();
        //$return =
            include $header_file; // Can return a value with return()
        $this->output .= ob_get_clean();

         ######   ######   ######
        ##    ## ##    ## ##    ##
        ##       ##       ##
        ##        ######   ######
        ##             ##       ##
        ##    ## ##    ## ##    ##
         ######   ######   ######

        $this->output .= $this->cssFiles();


        ##    ##    ###    ##     ## ########     ###    ########
        ###   ##   ## ##   ##     ## ##     ##   ## ##   ##     ##
        ####  ##  ##   ##  ##     ## ##     ##  ##   ##  ##     ##
        ## ## ## ##     ## ##     ## ########  ##     ## ########
        ##  #### #########  ##   ##  ##     ## ######### ##   ##
        ##   ### ##     ##   ## ##   ##     ## ##     ## ##    ##
        ##    ## ##     ##    ###    ########  ##     ## ##     ##

        $navbar_file = __DIR__ . '/../web/themes/' . $this->data->theme . '/html/navbar.php';
        ob_start();
        //$return =
        include $navbar_file; // Can return a value with return()
        $this->output .= ob_get_clean();

        // Use the following body (<main></main>) container from a body template in the theme. Use this as well as the
        // NavBar, Header and Footer templates in conjunction with elements from the page.json which will then be
        // configurable using the page editor tool (Todo, this will be a replaceable vendor package as well)

        $this->output .= "
        <main role=\"main\" class=\"container\">
          <div class=\"starter-template\">
            <h1>Bootstrap starter template</h1>
            <p class=\"lead\">{$this->html}</p>
          </div>
        </main>
        ";

              ##    ###    ##     ##    ###     ######   ######  ########  #### ########  ########
              ##   ## ##   ##     ##   ## ##   ##    ## ##    ## ##     ##  ##  ##     ##    ##
              ##  ##   ##  ##     ##  ##   ##  ##       ##       ##     ##  ##  ##     ##    ##
              ## ##     ## ##     ## ##     ##  ######  ##       ########   ##  ########     ##
        ##    ## #########  ##   ##  #########       ## ##       ##   ##    ##  ##           ##
        ##    ## ##     ##   ## ##   ##     ## ##    ## ##    ## ##    ##   ##  ##           ##
         ######  ##     ##    ###    ##     ##  ######   ######  ##     ## #### ##           ##

        $this->output .= $this->jsFiles();

        ########  #######   #######  ######## ######## ########
        ##       ##     ## ##     ##    ##    ##       ##     ##
        ##       ##     ## ##     ##    ##    ##       ##     ##
        ######   ##     ## ##     ##    ##    ######   ########
        ##       ##     ## ##     ##    ##    ##       ##   ##
        ##       ##     ## ##     ##    ##    ##       ##    ##
        ##        #######   #######     ##    ######## ##     ##


        $footer_file = __DIR__ . '/../web/themes/' . $this->data->theme . '/html/footer.php';
        ob_start();
        //$return =
        include $footer_file; // Can return a value with return()
        $this->output .= ob_get_clean();

        return true;

    }

     ######   ######   ######  ######## #### ##       ########  ######
    ##    ## ##    ## ##    ## ##        ##  ##       ##       ##    ##
    ##       ##       ##       ##        ##  ##       ##       ##
    ##        ######   ######  ######    ##  ##       ######    ######
    ##             ##       ## ##        ##  ##       ##             ##
    ##    ## ##    ## ##    ## ##        ##  ##       ##       ##    ##
     ######   ######   ######  ##       #### ######## ########  ######

    public function cssFiles() {

        $output = '';

        foreach ($this->css as $file) {

            $output .= "<link media=\"all\" rel=\"stylesheet\" href=\"$file\" />\n";

        }
        return $output;
    }

          ##  ######  ######## #### ##       ########  ######
          ## ##    ## ##        ##  ##       ##       ##    ##
          ## ##       ##        ##  ##       ##       ##
          ##  ######  ######    ##  ##       ######    ######
    ##    ##       ## ##        ##  ##       ##             ##
    ##    ## ##    ## ##        ##  ##       ##       ##    ##
     ######   ######  ##       #### ######## ########  ######

    public function jsFiles() {

        $output = '';

        foreach ($this->js as $file) {

            $output .= "<script defer=\"defer\"  type=\"application/javascript\" src=\"$file\" /></script>\n";

        }
        return $output;
    }

    ########     ###     ######   ########
    ##     ##   ## ##   ##    ##  ##
    ##     ##  ##   ##  ##        ##
    ########  ##     ## ##   #### ######
    ##        ######### ##    ##  ##
    ##        ##     ## ##    ##  ##
    ##        ##     ##  ######   ########

    public function page() {

        $this->json = $this->json();

        $this->data = json_decode($this->json);

        return true;

    }


          ##  ######   #######  ##    ##
          ## ##    ## ##     ## ###   ##
          ## ##       ##     ## ####  ##
          ##  ######  ##     ## ## ## ##
    ##    ##       ## ##     ## ##  ####
    ##    ## ##    ## ##     ## ##   ###
     ######   ######   #######  ##    ##


    public function json() {

        # If the page exists, return the json data for the page

        # otherwise return a blank template

        $page = file_get_contents('../pages/' . $this->site . '/' . $this->page . '.json');

        if (!$page) {

            $this->abort(404,'Page Not Found');

        } else {

            return($page);

        }
    }
}
