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

namespace spriebsch\streamwrapper;

class StreamWrapperProxy
{
    /**
     * @param string
     */
    protected $wrapperName;

    /**
     * @param string
     */
    protected $wrapperClass;
    
    /**
     * @param array
     */
    protected $wrapperParameters;

    /**
     * Note: wrapperParameters must include *all* parameters. set to null those that should not be set in the constructor.
     *
     * @param string $wrapperName Stream protocol name
     * @param string $wrapperClass Stream wrapper class
     * @param array $wrapperParameters Associative array of named "constructor" parameters for the stream wrapper
     */
    public function __construct($wrapperName, $wrapperClass, array $wrapperParameters = array())
    {
        if (!is_string($wrapperName)) {
            throw new Exception('Name "' . $wrapperName . '" must be a string', Exception::ILLEGAL_ARGUMENT);
        }
        $this->wrapperName = $wrapperName;

        if (!class_exists($wrapperClass)) {
            throw new Exception('Class "' . $wrapperClass . '" not defined', Exception::CLASS_NOT_DEFINED);
        }
        $this->wrapperClass = $wrapperClass;

        $this->wrapperParameters = $wrapperParameters;

        $this->registerStreamWrapper();
    }

    /**
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @return null
     */
    public function setWrapperParameter($name, $value)
    {
        call_user_func_array(array($this->wrapperClass, 'setParameter'), array($name, $value));
    }

    /**
     * Returns a stream wrapper parameter
     *
     * @return mixed
     */
    public function getWrapperParameter($name)
    {
        return call_user_func_array(array($this->wrapperClass, 'getParameter'), array($name));
    }
    
    /**
     * Intercepts setting of public attributes
     */
/*     
    public function __set($name, $value)
    {
        $this->setWrapperParameter($name, $value);
    }
*/

    /**
     * Intercepts getting of public attributes
     */
    public function __get($name)
    {
        return $this->getParameter($name);
    }

    /**
     * @todo Handle remote streams (third parameter to stream_wrapper_register)
     */
    protected function registerStreamWrapper()
    {
        if (in_array($this->wrapperName, stream_get_wrappers())) {
            stream_wrapper_unregister($this->wrapperName);
        }

        foreach ($this->wrapperParameters as $name => $value) {
            $this->setWrapperParameter($name, $value);
        }

        $result = stream_wrapper_register($this->wrapperName, $this->wrapperClass);
    }
}
