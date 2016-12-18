<?php

if (!function_exists('trump')) {
    function trump()
    {
        $client = new GuzzleHttp\Client();
        $res = $client->get('https://api.whatdoestrumpthink.com/api/v1/quotes/random');

        if (200 === $res->getStatusCode()) {
            echo strip_tags(json_decode($res->getBody()->getContents())->message).'<span style="font-weight: bold"> -- D. Trump</span>';
        }

        dump(...func_get_args());
    }
}
