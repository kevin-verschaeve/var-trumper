<?php

namespace VarTrumper;

class VarTrumper
{
    public static $addQuote = true;

    public static function trump()
    {
        if (true === self::$addQuote) {
            $response = file_get_contents('https://api.whatdoestrumpthink.com/api/v1/quotes/random');

            if ($response) {
                $content = json_decode($response, false);
                dump($content->message.' -- D. Trump');
            }
        }

        dump(...func_get_args());
    }

    public static function disableQuote()
    {
        self::$addQuote = false;
    }
}
