<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace VarTrumper\Cloner;

use VarTrumper\Caster\Caster;
use VarTrumper\Exception\ThrowingCasterException;

/**
 * AbstractCloner implements a generic caster mechanism for objects and resources.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
abstract class AbstractCloner implements ClonerInterface
{
    public static $defaultCasters = array(
        '__PHP_Incomplete_Class' => 'VarTrumper\Caster\Caster::castPhpIncompleteClass',

        'VarTrumper\Caster\CutStub' => 'VarTrumper\Caster\StubCaster::castStub',
        'VarTrumper\Caster\CutArrayStub' => 'VarTrumper\Caster\StubCaster::castCutArray',
        'VarTrumper\Caster\ConstStub' => 'VarTrumper\Caster\StubCaster::castStub',
        'VarTrumper\Caster\EnumStub' => 'VarTrumper\Caster\StubCaster::castEnum',

        'Closure' => 'VarTrumper\Caster\ReflectionCaster::castClosure',
        'Generator' => 'VarTrumper\Caster\ReflectionCaster::castGenerator',
        'ReflectionType' => 'VarTrumper\Caster\ReflectionCaster::castType',
        'ReflectionGenerator' => 'VarTrumper\Caster\ReflectionCaster::castReflectionGenerator',
        'ReflectionClass' => 'VarTrumper\Caster\ReflectionCaster::castClass',
        'ReflectionFunctionAbstract' => 'VarTrumper\Caster\ReflectionCaster::castFunctionAbstract',
        'ReflectionMethod' => 'VarTrumper\Caster\ReflectionCaster::castMethod',
        'ReflectionParameter' => 'VarTrumper\Caster\ReflectionCaster::castParameter',
        'ReflectionProperty' => 'VarTrumper\Caster\ReflectionCaster::castProperty',
        'ReflectionExtension' => 'VarTrumper\Caster\ReflectionCaster::castExtension',
        'ReflectionZendExtension' => 'VarTrumper\Caster\ReflectionCaster::castZendExtension',

        'Doctrine\Common\Persistence\ObjectManager' => 'VarTrumper\Caster\StubCaster::cutInternals',
        'Doctrine\Common\Proxy\Proxy' => 'VarTrumper\Caster\DoctrineCaster::castCommonProxy',
        'Doctrine\ORM\Proxy\Proxy' => 'VarTrumper\Caster\DoctrineCaster::castOrmProxy',
        'Doctrine\ORM\PersistentCollection' => 'VarTrumper\Caster\DoctrineCaster::castPersistentCollection',

        'DOMException' => 'VarTrumper\Caster\DOMCaster::castException',
        'DOMStringList' => 'VarTrumper\Caster\DOMCaster::castLength',
        'DOMNameList' => 'VarTrumper\Caster\DOMCaster::castLength',
        'DOMImplementation' => 'VarTrumper\Caster\DOMCaster::castImplementation',
        'DOMImplementationList' => 'VarTrumper\Caster\DOMCaster::castLength',
        'DOMNode' => 'VarTrumper\Caster\DOMCaster::castNode',
        'DOMNameSpaceNode' => 'VarTrumper\Caster\DOMCaster::castNameSpaceNode',
        'DOMDocument' => 'VarTrumper\Caster\DOMCaster::castDocument',
        'DOMNodeList' => 'VarTrumper\Caster\DOMCaster::castLength',
        'DOMNamedNodeMap' => 'VarTrumper\Caster\DOMCaster::castLength',
        'DOMCharacterData' => 'VarTrumper\Caster\DOMCaster::castCharacterData',
        'DOMAttr' => 'VarTrumper\Caster\DOMCaster::castAttr',
        'DOMElement' => 'VarTrumper\Caster\DOMCaster::castElement',
        'DOMText' => 'VarTrumper\Caster\DOMCaster::castText',
        'DOMTypeinfo' => 'VarTrumper\Caster\DOMCaster::castTypeinfo',
        'DOMDomError' => 'VarTrumper\Caster\DOMCaster::castDomError',
        'DOMLocator' => 'VarTrumper\Caster\DOMCaster::castLocator',
        'DOMDocumentType' => 'VarTrumper\Caster\DOMCaster::castDocumentType',
        'DOMNotation' => 'VarTrumper\Caster\DOMCaster::castNotation',
        'DOMEntity' => 'VarTrumper\Caster\DOMCaster::castEntity',
        'DOMProcessingInstruction' => 'VarTrumper\Caster\DOMCaster::castProcessingInstruction',
        'DOMXPath' => 'VarTrumper\Caster\DOMCaster::castXPath',

        'XmlReader' => 'VarTrumper\Caster\XmlReaderCaster::castXmlReader',

        'ErrorException' => 'VarTrumper\Caster\ExceptionCaster::castErrorException',
        'Exception' => 'VarTrumper\Caster\ExceptionCaster::castException',
        'Error' => 'VarTrumper\Caster\ExceptionCaster::castError',
        'Symfony\Component\DependencyInjection\ContainerInterface' => 'VarTrumper\Caster\StubCaster::cutInternals',
        'VarTrumper\Exception\ThrowingCasterException' => 'VarTrumper\Caster\ExceptionCaster::castThrowingCasterException',
        'VarTrumper\Caster\TraceStub' => 'VarTrumper\Caster\ExceptionCaster::castTraceStub',
        'VarTrumper\Caster\FrameStub' => 'VarTrumper\Caster\ExceptionCaster::castFrameStub',

        'PHPUnit_Framework_MockObject_MockObject' => 'VarTrumper\Caster\StubCaster::cutInternals',
        'Prophecy\Prophecy\ProphecySubjectInterface' => 'VarTrumper\Caster\StubCaster::cutInternals',
        'Mockery\MockInterface' => 'VarTrumper\Caster\StubCaster::cutInternals',

        'PDO' => 'VarTrumper\Caster\PdoCaster::castPdo',
        'PDOStatement' => 'VarTrumper\Caster\PdoCaster::castPdoStatement',

        'AMQPConnection' => 'VarTrumper\Caster\AmqpCaster::castConnection',
        'AMQPChannel' => 'VarTrumper\Caster\AmqpCaster::castChannel',
        'AMQPQueue' => 'VarTrumper\Caster\AmqpCaster::castQueue',
        'AMQPExchange' => 'VarTrumper\Caster\AmqpCaster::castExchange',
        'AMQPEnvelope' => 'VarTrumper\Caster\AmqpCaster::castEnvelope',

        'ArrayObject' => 'VarTrumper\Caster\SplCaster::castArrayObject',
        'SplDoublyLinkedList' => 'VarTrumper\Caster\SplCaster::castDoublyLinkedList',
        'SplFileInfo' => 'VarTrumper\Caster\SplCaster::castFileInfo',
        'SplFileObject' => 'VarTrumper\Caster\SplCaster::castFileObject',
        'SplFixedArray' => 'VarTrumper\Caster\SplCaster::castFixedArray',
        'SplHeap' => 'VarTrumper\Caster\SplCaster::castHeap',
        'SplObjectStorage' => 'VarTrumper\Caster\SplCaster::castObjectStorage',
        'SplPriorityQueue' => 'VarTrumper\Caster\SplCaster::castHeap',
        'OuterIterator' => 'VarTrumper\Caster\SplCaster::castOuterIterator',

        'MongoCursorInterface' => 'VarTrumper\Caster\MongoCaster::castCursor',

        'Redis' => 'VarTrumper\Caster\RedisCaster::castRedis',
        'RedisArray' => 'VarTrumper\Caster\RedisCaster::castRedisArray',

        ':curl' => 'VarTrumper\Caster\ResourceCaster::castCurl',
        ':dba' => 'VarTrumper\Caster\ResourceCaster::castDba',
        ':dba persistent' => 'VarTrumper\Caster\ResourceCaster::castDba',
        ':gd' => 'VarTrumper\Caster\ResourceCaster::castGd',
        ':mysql link' => 'VarTrumper\Caster\ResourceCaster::castMysqlLink',
        ':pgsql large object' => 'VarTrumper\Caster\PgSqlCaster::castLargeObject',
        ':pgsql link' => 'VarTrumper\Caster\PgSqlCaster::castLink',
        ':pgsql link persistent' => 'VarTrumper\Caster\PgSqlCaster::castLink',
        ':pgsql result' => 'VarTrumper\Caster\PgSqlCaster::castResult',
        ':process' => 'VarTrumper\Caster\ResourceCaster::castProcess',
        ':stream' => 'VarTrumper\Caster\ResourceCaster::castStream',
        ':stream-context' => 'VarTrumper\Caster\ResourceCaster::castStreamContext',
        ':xml' => 'VarTrumper\Caster\XmlResourceCaster::castXml',
    );

    protected $maxItems = 2500;
    protected $maxString = -1;
    protected $useExt;

    private $casters = array();
    private $prevErrorHandler;
    private $classInfo = array();
    private $filter = 0;

    /**
     * @param callable[]|null $casters A map of casters
     *
     * @see addCasters
     */
    public function __construct(array $casters = null)
    {
        if (null === $casters) {
            $casters = static::$defaultCasters;
        }
        $this->addCasters($casters);
        $this->useExt = extension_loaded('symfony_debug');
    }

    /**
     * Adds casters for resources and objects.
     *
     * Maps resources or objects types to a callback.
     * Types are in the key, with a callable caster for value.
     * Resource types are to be prefixed with a `:`,
     * see e.g. static::$defaultCasters.
     *
     * @param callable[] $casters A map of casters
     */
    public function addCasters(array $casters)
    {
        foreach ($casters as $type => $callback) {
            $this->casters[strtolower($type)][] = $callback;
        }
    }

    /**
     * Sets the maximum number of items to clone past the first level in nested structures.
     *
     * @param int $maxItems
     */
    public function setMaxItems($maxItems)
    {
        $this->maxItems = (int) $maxItems;
    }

    /**
     * Sets the maximum cloned length for strings.
     *
     * @param int $maxString
     */
    public function setMaxString($maxString)
    {
        $this->maxString = (int) $maxString;
    }

    /**
     * Clones a PHP variable.
     *
     * @param mixed $var    Any PHP variable
     * @param int   $filter A bit field of Caster::EXCLUDE_* constants
     *
     * @return Data The cloned variable represented by a Data object
     */
    public function cloneVar($var, $filter = 0)
    {
        $this->prevErrorHandler = set_error_handler(function ($type, $msg, $file, $line, $context) {
            if (E_RECOVERABLE_ERROR === $type || E_USER_ERROR === $type) {
                // Cloner never dies
                throw new \ErrorException($msg, 0, $type, $file, $line);
            }

            if ($this->prevErrorHandler) {
                return call_user_func($this->prevErrorHandler, $type, $msg, $file, $line, $context);
            }

            return false;
        });
        $this->filter = $filter;

        try {
            $data = $this->doClone($var);
        } catch (\Exception $e) {
        }
        restore_error_handler();
        $this->prevErrorHandler = null;

        if (isset($e)) {
            throw $e;
        }

        return new Data($data);
    }

    /**
     * Effectively clones the PHP variable.
     *
     * @param mixed $var Any PHP variable
     *
     * @return array The cloned variable represented in an array
     */
    abstract protected function doClone($var);

    /**
     * Casts an object to an array representation.
     *
     * @param Stub $stub     The Stub for the casted object
     * @param bool $isNested True if the object is nested in the dumped structure
     *
     * @return array The object casted as array
     */
    protected function castObject(Stub $stub, $isNested)
    {
        $obj = $stub->value;
        $class = $stub->class;

        if (isset($class[15]) && "\0" === $class[15] && 0 === strpos($class, "class@anonymous\x00")) {
            $stub->class = get_parent_class($class).'@anonymous';
        }
        if (isset($this->classInfo[$class])) {
            $classInfo = $this->classInfo[$class];
        } else {
            $classInfo = array(
                new \ReflectionClass($class),
                array_reverse(array($class => $class) + class_parents($class) + class_implements($class) + array('*' => '*')),
            );

            $this->classInfo[$class] = $classInfo;
        }

        $a = $this->callCaster('VarTrumper\Caster\Caster::castObject', $obj, $classInfo[0], null, $isNested);

        foreach ($classInfo[1] as $p) {
            if (!empty($this->casters[$p = strtolower($p)])) {
                foreach ($this->casters[$p] as $p) {
                    $a = $this->callCaster($p, $obj, $a, $stub, $isNested);
                }
            }
        }

        return $a;
    }

    /**
     * Casts a resource to an array representation.
     *
     * @param Stub $stub     The Stub for the casted resource
     * @param bool $isNested True if the object is nested in the dumped structure
     *
     * @return array The resource casted as array
     */
    protected function castResource(Stub $stub, $isNested)
    {
        $a = array();
        $res = $stub->value;
        $type = $stub->class;

        if (!empty($this->casters[':'.$type])) {
            foreach ($this->casters[':'.$type] as $c) {
                $a = $this->callCaster($c, $res, $a, $stub, $isNested);
            }
        }

        return $a;
    }

    /**
     * Calls a custom caster.
     *
     * @param callable        $callback The caster
     * @param object|resource $obj      The object/resource being casted
     * @param array           $a        The result of the previous cast for chained casters
     * @param Stub            $stub     The Stub for the casted object/resource
     * @param bool            $isNested True if $obj is nested in the dumped structure
     *
     * @return array The casted object/resource
     */
    private function callCaster($callback, $obj, $a, $stub, $isNested)
    {
        try {
            $cast = call_user_func($callback, $obj, $a, $stub, $isNested, $this->filter);

            if (is_array($cast)) {
                $a = $cast;
            }
        } catch (\Exception $e) {
            $a = array((Stub::TYPE_OBJECT === $stub->type ? Caster::PREFIX_VIRTUAL : '').'⚠' => new ThrowingCasterException($e)) + $a;
        }

        return $a;
    }
}
