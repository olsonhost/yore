<?php

namespace App;

class APIController extends Library {

    public function __construct() {

        $this->init();

        //var_dump([$this->site,$this->page,$this->arg1,$this->arg2,$this->arg3]);
        // https://yoreweb.com/ho/ha/this/that/tother
        // array(5) { [0]=> string(2) "ho" [1]=> string(2) "ha" [2]=> string(4) "this" [3]=> string(4) "that" [4]=> string(6) "tother" }


    }

}