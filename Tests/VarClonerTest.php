<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace VarTrumper\Tests;

use VarTrumper\Cloner\VarCloner;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class VarClonerTest extends \PHPUnit_Framework_TestCase
{
    public function testMaxIntBoundary()
    {
        $data = array(PHP_INT_MAX => 123);

        $cloner = new VarCloner();
        $clone = $cloner->cloneVar($data);

        $expected = <<<EOTXT
VarTrumper\Cloner\Data Object
(
    [data:VarTrumper\Cloner\Data:private] => Array
        (
            [0] => Array
                (
                    [0] => VarTrumper\Cloner\Stub Object
                        (
                            [type] => array
                            [class] => assoc
                            [value] => 1
                            [cut] => 0
                            [handle] => 0
                            [refCount] => 0
                            [position] => 1
                            [attr] => Array
                                (
                                )

                        )

                )

            [1] => Array
                (
                    [%s] => 123
                )

        )

    [position:VarTrumper\Cloner\Data:private] => 0
    [key:VarTrumper\Cloner\Data:private] => 0
    [maxDepth:VarTrumper\Cloner\Data:private] => 20
    [maxItemsPerDepth:VarTrumper\Cloner\Data:private] => -1
    [useRefHandles:VarTrumper\Cloner\Data:private] => -1
)

EOTXT;
        $this->assertSame(sprintf($expected, PHP_INT_MAX), print_r($clone, true));
    }

    public function testClone()
    {
        $json = json_decode('{"1":{"var":"val"},"2":{"var":"val"}}');

        $cloner = new VarCloner();
        $clone = $cloner->cloneVar($json);

        $expected = <<<EOTXT
VarTrumper\Cloner\Data Object
(
    [data:VarTrumper\Cloner\Data:private] => Array
        (
            [0] => Array
                (
                    [0] => VarTrumper\Cloner\Stub Object
                        (
                            [type] => object
                            [class] => stdClass
                            [value] => 
                            [cut] => 0
                            [handle] => %i
                            [refCount] => 0
                            [position] => 1
                            [attr] => Array
                                (
                                )

                        )

                )

            [1] => Array
                (
                    [\000+\0001] => VarTrumper\Cloner\Stub Object
                        (
                            [type] => object
                            [class] => stdClass
                            [value] => 
                            [cut] => 0
                            [handle] => %i
                            [refCount] => 0
                            [position] => 2
                            [attr] => Array
                                (
                                )

                        )

                    [\000+\0002] => VarTrumper\Cloner\Stub Object
                        (
                            [type] => object
                            [class] => stdClass
                            [value] => 
                            [cut] => 0
                            [handle] => %i
                            [refCount] => 0
                            [position] => 3
                            [attr] => Array
                                (
                                )

                        )

                )

            [2] => Array
                (
                    [\000+\000var] => val
                )

            [3] => Array
                (
                    [\000+\000var] => val
                )

        )

    [position:VarTrumper\Cloner\Data:private] => 0
    [key:VarTrumper\Cloner\Data:private] => 0
    [maxDepth:VarTrumper\Cloner\Data:private] => 20
    [maxItemsPerDepth:VarTrumper\Cloner\Data:private] => -1
    [useRefHandles:VarTrumper\Cloner\Data:private] => -1
)

EOTXT;
        $this->assertStringMatchesFormat($expected, print_r($clone, true));
    }

    public function testJsonCast()
    {
        $data = (array) json_decode('{"1":{}}');

        $cloner = new VarCloner();
        $clone = $cloner->cloneVar($data);

        $expected = <<<'EOTXT'
object(VarTrumper\Cloner\Data)#%i (6) {
  ["data":"VarTrumper\Cloner\Data":private]=>
  array(2) {
    [0]=>
    array(1) {
      [0]=>
      object(VarTrumper\Cloner\Stub)#%i (8) {
        ["type"]=>
        string(5) "array"
        ["class"]=>
        string(5) "assoc"
        ["value"]=>
        int(1)
        ["cut"]=>
        int(0)
        ["handle"]=>
        int(0)
        ["refCount"]=>
        int(0)
        ["position"]=>
        int(1)
        ["attr"]=>
        array(0) {
        }
      }
    }
    [1]=>
    array(1) {
      ["1"]=>
      object(VarTrumper\Cloner\Stub)#%i (8) {
        ["type"]=>
        string(6) "object"
        ["class"]=>
        string(8) "stdClass"
        ["value"]=>
        NULL
        ["cut"]=>
        int(0)
        ["handle"]=>
        int(%i)
        ["refCount"]=>
        int(0)
        ["position"]=>
        int(0)
        ["attr"]=>
        array(0) {
        }
      }
    }
  }
  ["position":"VarTrumper\Cloner\Data":private]=>
  int(0)
  ["key":"VarTrumper\Cloner\Data":private]=>
  int(0)
  ["maxDepth":"VarTrumper\Cloner\Data":private]=>
  int(20)
  ["maxItemsPerDepth":"VarTrumper\Cloner\Data":private]=>
  int(-1)
  ["useRefHandles":"VarTrumper\Cloner\Data":private]=>
  int(-1)
}

EOTXT;
        ob_start();
        var_dump($clone);
        $this->assertStringMatchesFormat($expected, ob_get_clean());
    }

    public function testCaster()
    {
        $cloner = new VarCloner(array(
            '*' => function ($obj, $array) {
                return array('foo' => 123);
            },
            __CLASS__ => function ($obj, $array) {
                ++$array['foo'];

                return $array;
            },
        ));
        $clone = $cloner->cloneVar($this);

        $expected = <<<EOTXT
VarTrumper\Cloner\Data Object
(
    [data:VarTrumper\Cloner\Data:private] => Array
        (
            [0] => Array
                (
                    [0] => VarTrumper\Cloner\Stub Object
                        (
                            [type] => object
                            [class] => %s
                            [value] => 
                            [cut] => 0
                            [handle] => %i
                            [refCount] => 0
                            [position] => 1
                            [attr] => Array
                                (
                                )

                        )

                )

            [1] => Array
                (
                    [foo] => 124
                )

        )

    [position:VarTrumper\Cloner\Data:private] => 0
    [key:VarTrumper\Cloner\Data:private] => 0
    [maxDepth:VarTrumper\Cloner\Data:private] => 20
    [maxItemsPerDepth:VarTrumper\Cloner\Data:private] => -1
    [useRefHandles:VarTrumper\Cloner\Data:private] => -1
)

EOTXT;
        $this->assertStringMatchesFormat($expected, print_r($clone, true));
    }
}
