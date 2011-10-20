<?php

namespace spriebsch\streamwrapper;

require __DIR__ . '/src/autoload.php';

class MyWrapper extends StreamWrapper implements StreamWrapperInterface
{
    protected static $name;
    protected static $basepath;
    protected static $reverse = false;

    protected $file;
    protected $length;
    protected $position = 0;

    public function stream_open($path, $mode, $options, &$opened_path)
    {   
        $path = substr($path, strlen(static::$name . '://'));

        $filename = static::$basepath . '/' . $path;

        $this->file = file_get_contents($filename);
        $this->length = strlen($this->file);

        if (static::$reverse) {
            $this->file = strrev($this->file);
        }

        return $this->file !== false;
    }

    public function stream_read($count)
    {
        $result = substr($this->file, $this->position, $count);
        $this->position += $count;
        return $result;
    }

    public function stream_stat()
    {
    }

    public function stream_eof()
    {
        return $this->position > $this->length;
    }
}

$p = new StreamWrapperProxy('foo', 'spriebsch\\streamwrapper\\MyWrapper', array(
    'basepath' => __DIR__ . '/tests/testdata',
    'name' => 'foo',
));

$result = file_get_contents('foo://hello_world');
var_dump($result);

$p->setWrapperParameter('reverse', true);

$result = file_get_contents('foo://hello_world');
var_dump($result);
