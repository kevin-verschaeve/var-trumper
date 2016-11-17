<?php

namespace VarTrumper\Exception;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ThrowingCasterException extends \Exception
{
    /**
     * @param \Exception $prev The exception thrown from the caster
     */
    public function __construct(\Exception $prev)
    {
        parent::__construct('Unexpected '.get_class($prev).' thrown from a caster: '.$prev->getMessage(), 0, $prev);
    }
}
