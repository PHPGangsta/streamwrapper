<?php

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

    public function setParameter($name, $value)
    {
        call_user_func_array(array($this->wrapperClass, 'setParameter'), array($name, $value));
    }
    
    /**
     * Intercepts setting of public attributes
     * @todo magic getter that retrieves stuff from streamwrapper
     */
    public function __set($name, $value)
    {
        if (in_array($name, array_keys($this->wrapperParameters))) {
            $this->setParameter($name, $value);
        }
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
            $this->setParameter($name, $value);
        }

        $result = stream_wrapper_register($this->wrapperName, $this->wrapperClass);
    }
}
