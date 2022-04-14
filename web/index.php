<?php

# This is an example of a plain website that uses ECT

# Or, for example, you could use ECT in a Wordpress Plugin

use App\Controller;
use Olsonhost\Ect\Init;

require '../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

$C = new Controller;

echo $C->page;



// This is how we would instantiate ECT (which instantiates Twilio)

#$ECT = new Init();

// This invokes the Twilio webhook which invokes $this((Twilio))->ect->test(); which outputs *** TEST!!! ***

#$ECT->whtest();

#exit('<img style="width:72px" src="/images/spronzer.png"><br/>Halo Welt');
