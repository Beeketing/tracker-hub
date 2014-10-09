<?php
function __autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    // Add src path
    $filePath = __DIR__ . '/../src/' . $fileName;

    require $filePath;
}

/**
 * Debug
 * @param bool|mixed $var
 * @param int $trace
 * @param bool $showHtml
 * @param bool $showFrom
 */
function vd($var = false, $trace = 1, $showHtml = false, $showFrom = true) {
    if ($showFrom) {
        $calledFrom = debug_backtrace();
        for ($i = 0; $i < $trace; $i++) {
            if (!isset($calledFrom[$i]['file'])) {
                break;
            }
            echo substr($calledFrom[$i]['file'], 1);
            echo "\n" . ' (line <strong>' . $calledFrom[$i]['line'] . '</strong>)';
            echo "<br />";
        }
    }
    echo "<pre class=\"cake-debug\">\n";

    $var = var_dump($var);
    if ($showHtml) {
        $var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
    }
    echo $var . "\n</pre>\n";
}

/**
 * Debug then die
 * @param mixed|bool $var
 * @param int $trace
 * @param bool $showHtml
 * @param bool $showFrom
 */
function vdd($var = false, $trace = 1, $showHtml = false, $showFrom = true) {
    if ($showFrom) {
        $calledFrom = debug_backtrace();
        for ($i = 0; $i < $trace; $i++) {
            if (!isset($calledFrom[$i]['file'])) {
                break;
            }
            echo substr($calledFrom[$i]['file'], 1);
            echo "\n" . ' (line <strong>' . $calledFrom[$i]['line'] . '</strong>)';
            echo "<br />";
        }
    }
    echo "<pre class=\"cake-debug\">\n";

    $var = var_dump($var);
    if ($showHtml) {
        $var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
    }
    echo $var . "\n</pre>\n";
    die;
}