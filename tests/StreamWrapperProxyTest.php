<?php
/**
 * Copyright (c) 2009-2011 Stefan Priebsch <stefan@priebsch.de>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *
 *   * Neither the name of Stefan Priebsch nor the names of contributors
 *     may be used to endorse or promote products derived from this software
 *     without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER ORCONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
 * OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    streamwrapper
 * @author     Stefan Priebsch <stefan@priebsch.de>
 * @copyright  Stefan Priebsch <stefan@priebsch.de>. All rights reserved.
 * @license    BSD License
 */

namespace spriebsch\streamwrapper\tests;

use spriebsch\streamwrapper\StreamWrapperProxy;

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
        $this->assertEquals('A', TestStreamWrapper::$a);
        $this->assertEquals('B', TestStreamWrapper::$b);
    }

    /**
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::__set
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::setParameter
     */
    public function testSetInterceptorSetsParameterInStreamWrapper()
    {
        $this->proxy = new StreamWrapperProxy('name', 'spriebsch\\streamwrapper\\tests\\TestStreamWrapper', array('a' => 'A', 'b' => 'B'));
        $this->proxy->a = 'A';
        $this->assertEquals('A', TestStreamWrapper::$a);
    }

    /**
     * @expectedException spriebsch\streamwrapper\Exception
     * @expectedExceptionCode spriebsch\streamwrapper\Exception::UNKNOWN_PARAMETER
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::__get
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::getParameter
     */
    public function testGetInterceptorThrowsExceptionWhenParameterIsUnknown()
    {
        $this->proxy = new StreamWrapperProxy('name', 'spriebsch\\streamwrapper\\tests\\TestStreamWrapper', array('a' => 'A', 'b' => 'B'));
        $temp = $this->proxy->c;
    }
    
    /**
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::__get
     * @covers spriebsch\streamwrapper\StreamWrapperProxy::getParameter
     */
    public function testGetInterceptorGetsParameterFromStreamWrapper()
    {
        $this->proxy = new StreamWrapperProxy('name', 'spriebsch\\streamwrapper\\tests\\TestStreamWrapper', array('a' => 'A', 'b' => 'B'));
        $this->assertEquals('A', $this->proxy->a);
    }    
}
