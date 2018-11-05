<?php

namespace VarTrumper;

use GuzzleHttp\Client as Guzzle;

class VarTrumper
{
    public static $addQuote = true;

    public static function trump()
    {
        if (true === self::$addQuote) {
            $response = (new Guzzle())->get('https://api.whatdoestrumpthink.com/api/v1/quotes/random');

            if (200 === $response->getStatusCode()) {
                dump(json_decode($response->getBody()->getContents())->message.' -- D. Trump');
            }
        }

        dump(...func_get_args());
    }

    public static function disableQuote()
    {
        self::$addQuote = false;
    }
}
