<?php

use VarTrumper\VarTrumper;

if (!function_exists('trump')) {
    function trump($var)
    {
        foreach (func_get_args() as $var) {
            VarTrumper::trump($var);
        }
    }
}
