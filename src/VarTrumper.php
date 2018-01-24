<?php

namespace VarTrumper;

use GuzzleHttp\Client as Guzzle;

class VarTrumper
{
    public static $addQuote = true;

    public static function trump()
    {
        if (true === self::$addQuote) {
            $client = new Guzzle();
            $res = $client->get('https://api.whatdoestrumpthink.com/api/v1/quotes/random');

            if (200 === $res->getStatusCode()) {
                self::displayQuote(json_decode($res->getBody()->getContents())->message);
            }
        }

        dump(...func_get_args());
    }

    public static function disableQuote()
    {
        self::$addQuote = false;
    }

    private static function displayQuote($quote)
    {
        printf('<p style="background: black;margin: 0; color: #56DB3A; padding: 5px; font-weight: bold">%s -- D. Trump</p>', $quote);
    }
}
