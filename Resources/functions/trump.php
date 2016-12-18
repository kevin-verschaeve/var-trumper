<?php

if (!function_exists('trump')) {
    function trump()
    {
        dump(...func_get_args());
    }
}
