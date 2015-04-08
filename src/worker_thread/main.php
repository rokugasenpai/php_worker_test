<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'worker_thread.php');

if ($argc != 3 || (string)(int)$argv[1] !== $argv[1] || (string)(int)$argv[2] !== $argv[2]) {
    echo('usage: main.php each times' . PHP_EOL);
    echo('example: main.php 25000 4');
}

$each = (int)$argv[1];
$times = (int)$argv[2];

$workers = array();

for ($i = 1; $i <= $times; $i++) {
    $workers[$i - 1] = new WorkerThread($i, $each, $times);
    $workers[$i - 1]->start();
}
