<?php
/**
 * Autoloader
 *
 * @category  Autoloader
 * @description This is a default autoloader for the entire onebytesolutions library
 * @author    Jason Bryan <jason@onebytesolutions.com>
 * @copyright Copyright (c) 2017
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/onebytesolutions/database-library
 * @version   1.0
 */
spl_autoload_register(function($className)
{
    $class = ltrim($className, '\\');
    $file  = '';
    $namespace = '';
    if ($lastPos = strrpos($class, '\\')) {
        $namespace = substr($class, 0, $lastPos);
        $class = substr($class, $lastPos + 1);
        $file  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $file .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

    require $file;
});