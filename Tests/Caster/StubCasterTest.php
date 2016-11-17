<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace VarTrumper\Tests\Caster;

use VarTrumper\Caster\ArgsStub;
use VarTrumper\Caster\ClassStub;
use VarTrumper\Caster\LinkStub;
use VarTrumper\Cloner\VarCloner;
use VarTrumper\Dumper\HtmlDumper;
use VarTrumper\Test\VarDumperTestTrait;
use VarTrumper\Tests\Fixtures\FooInterface;

class StubCasterTest extends \PHPUnit_Framework_TestCase
{
    use VarDumperTestTrait;

    public function testArgsStubWithDefaults($foo = 234, $bar = 456)
    {
        $args = array(new ArgsStub(array(123), __FUNCTION__, __CLASS__));

        $expectedDump = <<<'EODUMP'
array:1 [
  0 => {
    $foo: 123
  }
]
EODUMP;

        $this->assertDumpMatchesFormat($expectedDump, $args);
    }

    public function testArgsStubWithExtraArgs($foo = 234)
    {
        $args = array(new ArgsStub(array(123, 456), __FUNCTION__, __CLASS__));

        $expectedDump = <<<'EODUMP'
array:1 [
  0 => {
    $foo: 123
    ...: {
      456
    }
  }
]
EODUMP;

        $this->assertDumpMatchesFormat($expectedDump, $args);
    }

    public function testArgsStubNoParamWithExtraArgs()
    {
        $args = array(new ArgsStub(array(123), __FUNCTION__, __CLASS__));

        $expectedDump = <<<'EODUMP'
array:1 [
  0 => {
    123
  }
]
EODUMP;

        $this->assertDumpMatchesFormat($expectedDump, $args);
    }

    public function testArgsStubWithClosure()
    {
        $args = array(new ArgsStub(array(123), '{closure}', null));

        $expectedDump = <<<'EODUMP'
array:1 [
  0 => {
    123
  }
]
EODUMP;

        $this->assertDumpMatchesFormat($expectedDump, $args);
    }

    public function testLinkStub()
    {
        $var = array(new LinkStub(__CLASS__, 0, __FILE__));

        $cloner = new VarCloner();
        $dumper = new HtmlDumper();
        $dumper->setDumpHeader('<foo></foo>');
        $dumper->setDumpBoundaries('<bar>', '</bar>');
        $dumper->setDisplayOptions(array('fileLinkFormat' => '%f:%l'));
        $dump = $dumper->dump($cloner->cloneVar($var), true);

        $expectedDump = <<<'EODUMP'
<foo></foo><bar><span class=sf-dump-note>array:1</span> [<samp>
  <span class=sf-dump-index>0</span> => "<a href="%sStubCasterTest.php:0"><span class=sf-dump-str title="55 characters">VarTrumper\Tests\Caster\StubCasterTest</span></a>"
</samp>]
</bar>
EODUMP;

        $this->assertStringMatchesFormat($expectedDump, $dump);
    }

    public function testClassStub()
    {
        $var = array(new ClassStub('hello', array(FooInterface::class, 'foo')));

        $cloner = new VarCloner();
        $dumper = new HtmlDumper();
        $dumper->setDumpHeader('<foo></foo>');
        $dumper->setDumpBoundaries('<bar>', '</bar>');
        $dump = $dumper->dump($cloner->cloneVar($var), true, array('fileLinkFormat' => '%f:%l'));

        $expectedDump = <<<'EODUMP'
<foo></foo><bar><span class=sf-dump-note>array:1</span> [<samp>
  <span class=sf-dump-index>0</span> => "<a href="%sFooInterface.php:10"><span class=sf-dump-str title="5 characters">hello</span></a>"
</samp>]
</bar>
EODUMP;

        $this->assertStringMatchesFormat($expectedDump, $dump);
    }

    public function testClassStubWithNotExistingClass()
    {
        $var = array(new ClassStub(NotExisting::class));

        $cloner = new VarCloner();
        $dumper = new HtmlDumper();
        $dumper->setDumpHeader('<foo></foo>');
        $dumper->setDumpBoundaries('<bar>', '</bar>');
        $dump = $dumper->dump($cloner->cloneVar($var), true);

        $expectedDump = <<<'EODUMP'
<foo></foo><bar><span class=sf-dump-note>array:1</span> [<samp>
  <span class=sf-dump-index>0</span> => "<span class=sf-dump-str title="VarTrumper\Tests\Caster\NotExisting
52 characters"><span class=sf-dump-ellipsis>VarTrumper\Tests\Caster</span>\NotExisting</span>"
</samp>]
</bar>
EODUMP;

        $this->assertStringMatchesFormat($expectedDump, $dump);
    }

    public function testClassStubWithNotExistingMethod()
    {
        $var = array(new ClassStub('hello', array(FooInterface::class, 'missing')));

        $cloner = new VarCloner();
        $dumper = new HtmlDumper();
        $dumper->setDumpHeader('<foo></foo>');
        $dumper->setDumpBoundaries('<bar>', '</bar>');
        $dump = $dumper->dump($cloner->cloneVar($var), true, array('fileLinkFormat' => '%f:%l'));

        $expectedDump = <<<'EODUMP'
<foo></foo><bar><span class=sf-dump-note>array:1</span> [<samp>
  <span class=sf-dump-index>0</span> => "<a href="%sFooInterface.php:5"><span class=sf-dump-str title="5 characters">hello</span></a>"
</samp>]
</bar>
EODUMP;

        $this->assertStringMatchesFormat($expectedDump, $dump);
    }
}
