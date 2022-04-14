<?php

# This is an example of a plain website that uses ECT

# Or, for example, you could use ECT in a Wordpress Plugin

use App\Controller;
use Olsonhost\Ect\Init;


require '../vendor/autoload.php';

$C = new Controller;






// This is how we would instantiate ECT (which instantiates Twilio)

#$ECT = new Init();

// This invokes the Twilio webhook which invokes $this((Twilio))->ect->test(); which outputs *** TEST!!! ***

#$ECT->whtest();

#exit('<img style="width:72px" src="/images/spronzer.png"><br/>Halo Welt');
