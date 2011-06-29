<?php

namespace spriebsch\streamwrapper\tests;

use spriebsch\streamwrapper\StreamWrapperProxy;
use spriebsch\streamwrapper\StreamWrapper;

class StreamWrapperProxyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException spriebsch\streamwrapper\Exception
     * @expectedExceptionCode spriebsch\streamwrapper\Exception::ILLEGAL_ARGUMENT
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::__construct
     */
    public function testConstructorThrowsExceptionWhenWrapperNameIsNotAString()
    {
        $this->proxy = new StreamWrapperProxy(null, null);
    }

    /**
     * @expectedException spriebsch\streamwrapper\Exception
     * @expectedExceptionCode spriebsch\streamwrapper\Exception::CLASS_NOT_DEFINED
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::__construct
     */
    public function testConstructorThrowsExceptionWhenClassNotDefined()
    {
        $this->proxy = new StreamWrapperProxy('name', 'does_not_exist');
    }

    /**
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::__construct
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::registerStreamWrapper
     */
    public function testRegistersStreamWrapper()
    {
        $this->proxy = new StreamWrapperProxy('name', 'spriebsch\\streamwrapper\\tests\\TestStreamWrapper');
        $this->assertContains('name', stream_get_wrappers());
    }

    /**
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::__construct
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::registerStreamWrapper
     */
    public function testUnRegistersStreamWrapperBeforeRegistering()
    {
        $this->proxy = new StreamWrapperProxy('name', 'spriebsch\\streamwrapper\\tests\\TestStreamWrapper');
        $this->proxy = new StreamWrapperProxy('name', 'spriebsch\\streamwrapper\\tests\\TestStreamWrapper');
        $this->assertContains('name', stream_get_wrappers());
    }

    /**
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::registerStreamWrapper
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::setParameter
     */
    public function testSetsStreamWrapperParameters()
    {
        $this->proxy = new StreamWrapperProxy('name', 'spriebsch\\streamwrapper\\tests\\TestStreamWrapper', array('a' => 'A', 'b' => 'B'));
        $this->assertContains('name', stream_get_wrappers());
    }
}

class TestStreamWrapper extends StreamWrapper
{
    protected static $a;
    protected static $b;
}
