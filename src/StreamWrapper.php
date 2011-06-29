<?php

namespace spriebsch\streamwrapper;

abstract class StreamWrapper
{
    protected static $foo;
    protected static $bar;
    protected static $baz;

    public static function setParameter($name, $value)
    {
        if (!in_array($name, (array_keys(get_class_vars(get_called_class()))))) {
            throw new \Exception('Parameter "' . $name . '" does not exist');
        }

           
        static::$$name = $value;
    }
    
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        var_dump(static::$foo);
    }
}
