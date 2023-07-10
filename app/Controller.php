<?php
/*

 ▄▄▄▄    ██▓    ▄▄▄       ▄████▄   ██ ▄█▀ ██▀███   █    ██   ██████  ██░ ██
▓█████▄ ▓██▒   ▒████▄    ▒██▀ ▀█   ██▄█▒ ▓██ ▒ ██▒ ██  ▓██▒▒██    ▒ ▓██░ ██▒
▒██▒ ▄██▒██░   ▒██  ▀█▄  ▒▓█    ▄ ▓███▄░ ▓██ ░▄█ ▒▓██  ▒██░░ ▓██▄   ▒██▀▀██░
▒██░█▀  ▒██░   ░██▄▄▄▄██ ▒▓▓▄ ▄██▒▓██ █▄ ▒██▀▀█▄  ▓▓█  ░██░  ▒   ██▒░▓█ ░██
░▓█  ▀█▓░██████▒▓█   ▓██▒▒ ▓███▀ ░▒██▒ █▄░██▓ ▒██▒▒▒█████▓ ▒██████▒▒░▓█▒░██▓
░▒▓███▀▒░ ▒░▓  ░▒▒   ▓▒█░░ ░▒ ▒  ░▒ ▒▒ ▓▒░ ▒▓ ░▒▓░░▒▓▒ ▒ ▒ ▒ ▒▓▒ ▒ ░ ▒ ░░▒░▒
▒░▒   ░ ░ ░ ▒  ░ ▒   ▒▒ ░  ░  ▒   ░ ░▒ ▒░  ░▒ ░ ▒░░░▒░ ░ ░ ░ ░▒  ░ ░ ▒ ░▒░ ░
 ░    ░   ░ ░    ░   ▒   ░        ░ ░░ ░   ░░   ░  ░░░ ░ ░ ░  ░  ░   ░  ░░ ░
 ░          ░  ░     ░  ░░ ░      ░  ░      ░        ░           ░   ░  ░  ░
      ░                  ░



*/


 ######   #######  ##    ## ######## ########   #######  ##       ##       ######## ########
##    ## ##     ## ###   ##    ##    ##     ## ##     ## ##       ##       ##       ##     ##
##       ##     ## ####  ##    ##    ##     ## ##     ## ##       ##       ##       ##     ##
##       ##     ## ## ## ##    ##    ########  ##     ## ##       ##       ######   ########
##       ##     ## ##  ####    ##    ##   ##   ##     ## ##       ##       ##       ##   ##
##    ## ##     ## ##   ###    ##    ##    ##  ##     ## ##       ##       ##       ##    ##
 ######   #######  ##    ##    ##    ##     ##  #######  ######## ######## ######## ##     ##

namespace App;

use Michelf\Markdown;
use Michelf\MarkdownExtra;

class Controller extends Library {

    public $page;

    public $data, $json;

    public $header, $footer, $html, $js = [], $css = [], $output;

    // public vars in parent class $params, $site, $name, $arg1, $arg2, $arg3, $debug = true;

    public function __construct() {

        $this->init();

        // var_dump([$this->site,$this->page,$this->arg1,$this->arg2,$this->arg3]);
        // https://yoreweb.com/ho/ha/this/that/tother
        // array(5) { [0]=> string(2) "ho" [1]=> string(2) "ha" [2]=> string(4) "this" [3]=> string(4) "that" [4]=> string(6) "tother" }

        $ok = $this->page();

        if ($ok != true) {
            $this->abort(500, $ok);
        }

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

        // no no no. Body is only rendered using @body or @data('body')
        # $this->html = $this->data->body;

        // default site is 'default'
        // default view name is 'home'
        // https://{domain}/{site=default}/{name=home}

        $default_view = '../pages/' . $this->site . '/views/' . $this->data->view . '.phat.php';

        $domain_view  = '../pages/_domains/' . $this->domain . '/' . $this->site . '/views/' . $this->data->view . '.phat.php';

        $view='';

        if (file_exists($domain_view)) {

            $this->html = file_get_contents($view=$domain_view); // This is our whole tire content

        } else {

            $this->html = file_get_contents($view=$default_view); // Just a template that displays @body()

        }

        // The html should be either data->view, data->views[] combined, or a default phat with @body()

        // If we want to keep Phat as separate project, send these vars to phat object rather than setting them here

        // Not sure cuz we may want these (i.e. for the edit button) for header, footer, multiple views, nav bar, whtvr

        $vars = get_defined_vars();

        // the main reason for doing this right now is so Phat knows what #view is, to use in @edit(#view)
        foreach ($vars as $var => $val) {
            $this->html = str_replace('#' . $var, $val, $this->html);
        }

        if ($this->data->markdown ?? false) {
            $this->html = MarkdownExtra::defaultTransform($this->html);
        }

        return true;

    }

       ###     ######   ######  ######## ##     ## ########  ##       ########
      ## ##   ##    ## ##    ## ##       ###   ### ##     ## ##       ##
     ##   ##  ##       ##       ##       #### #### ##     ## ##       ##
    ##     ##  ######   ######  ######   ## ### ## ########  ##       ######
    #########       ##       ## ##       ##     ## ##     ## ##       ##
    ##     ## ##    ## ##    ## ##       ##     ## ##     ## ##       ##
    ##     ##  ######   ######  ######## ##     ## ########  ######## ########

    public function assemble() {

        // include theme js and css (require it)

        $dir = __DIR__ . '/../web/themes/' . $this->data->theme . '/css/*.css';

        foreach (glob($dir) as $filename) {
            $this->css[] = explode('/../web', $filename)[1];
        }

        if (empty($this->css)) {

            $this->abort(500, "Missing Theme Stylesheet");

        }

        $dir = __DIR__ . '/../web/themes/' . $this->data->theme . '/js/*.js';

        foreach (glob($dir) as $filename) {
            $this->js[] = explode('/../web', $filename)[1];
        }

        if (empty($this->js)) {

            $this->abort(500, "Missing Theme Javascript");

        }

        // Include common js and css too

        $dir = __DIR__ . '/../web/css/*.css';

        foreach (glob($dir) as $filename) {
            $this->css[] = explode('/../web', $filename)[1];
        }

        $dir = __DIR__ . '/../web/js/*.js';

        foreach (glob($dir) as $filename) {
            $this->js[] = explode('/../web', $filename)[1];
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

//        $this->output .= "
//        <main role=\"main\" class=\"container\">
//          <div class=\"starter-template\">
//            <h1>Bootstrap starter template</h1>
//            <p class=\"lead\">{$this->html}</p>
//          </div>
//        </main>
//        ";

        $this->output .= $this->html;

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

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return true;
                break;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                return 'Unknown JSON error';
                break;
        }
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

        // default site is 'default'
        // default page name is 'home'
        // https://{domain}/{site=default}/{page=home}

        $default_page = '../pages/' . $this->site . '/' . $this->name . '.json';

        $domain_page  = '../pages/_domains/' . $this->domain . '/' . $this->site . '/' . $this->name . '.json';

        if (file_exists($domain_page)) {

            $page = file_get_contents($domain_page);

        } else {

            $page = file_get_contents($default_page);

        }

        if (!$page) {

            $this->abort(404,'Page Not Found');

        } else {

            return($page);

        }
    }
}
