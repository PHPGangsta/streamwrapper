<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart 
// this is an autogenerated file - do not edit
spl_autoload_register(
   function($class) {
      static $classes = null;
      if ($classes === null) {
         $classes = array(
            'spriebsch\\streamwrapper\\tests\\streamwrapperproxytest' => '/StreamWrapperProxyTest.php',
            'spriebsch\\streamwrapper\\tests\\streamwrappertest' => '/StreamWrapperTest.php',
            'spriebsch\\streamwrapper\\tests\\teststreamwrapper' => '/testdata/TestStreamWrapper.php'
          );
      }
      $cn = strtolower($class);
      if (isset($classes[$cn])) {
         require __DIR__ . $classes[$cn];
      }
   }
);
// @codeCoverageIgnoreEnd