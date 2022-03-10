<?php
function autoloader_register($class) {
    include 'Vendor/' . $class . '.php';
}

spl_autoload_register('autoloader_register');
?>