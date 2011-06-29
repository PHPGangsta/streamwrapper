<?php

namespace spriebsch\streamwrapper;

require __DIR__ . '/StreamWrapper.php';
require __DIR__ . '/StreamWrapperProxy.php';

class MyWrapper extends StreamWrapper
{
}

$p = new StreamWrapperProxy('foo', 'spriebsch\\streamwrapper\\MyWrapper', array(
    'foo' => 1,
    'bar' => 2,
    'baz' => 3,
));

$p->foo = 42;

$result = file_get_contents('foo://nonsense');

$p->foo = 44;

$result = file_get_contents('foo://nonsense');

// var_dump($p);
