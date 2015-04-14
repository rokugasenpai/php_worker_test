<?php

if ($argc != 3 || (string)(int)$argv[1] !== $argv[1] || (string)(int)$argv[2] !== $argv[2]) {
    echo('usage: main.php each times' . PHP_EOL);
    echo('example: main.php 25000 4' . PHP_EOL);
    exit(1);
}

if (ini_get('xdebug.profiler_enable')) {
    echo('maybe slow while xdebug is enabled' . PHP_EOL);
}

$each = (int)$argv[1];
$times = (int)$argv[2];

for ($i = 1; $i <= $times; $i++) {
    if (strpos(PHP_OS, 'WIN') !== FALSE) {
        $fp = popen('start php ' . __DIR__ . DIRECTORY_SEPARATOR . 'worker_process.php ' . (string)$i . ' ' . (string)$each . ' ' . (string)$times, 'r');
        pclose($fp);
    } else {
        exec('php ' . __DIR__ . DIRECTORY_SEPARATOR . 'worker_process.php ' . (string)$i . ' ' . (string)$each . ' ' . (string)$times . ' > /dev/null &');
    }
}