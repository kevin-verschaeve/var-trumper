<?php

namespace VarTrumper;

use VarTrumper\Cloner\VarCloner;
use VarTrumper\Dumper\CliDumper;
use VarTrumper\Dumper\HtmlDumper;

// Load the global trump() function
require_once __DIR__.'/Resources/functions/trump.php';

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class VarTrumper
{
    private static $handler;

    public static function trump($var)
    {
        if (null === self::$handler) {
            $cloner = new VarCloner();
            $trumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();
            self::$handler = function ($var) use ($cloner, $trumper) {
                $trumper->trump($cloner->cloneVar($var));
            };
        }

        return call_user_func(self::$handler, $var);
    }

    public static function setHandler(callable $callable = null)
    {
        $prevHandler = self::$handler;
        self::$handler = $callable;

        return $prevHandler;
    }
}
