<?php

if (!function_exists('trump')) {
    function trump()
    {
        \VarTrumper\VarTrumper::trump(...func_get_args());
    }
}
