<?php

$log_filepath = __DIR__ . DIRECTORY_SEPARATOR . 'main_' . $argv[2] .  '_' . $argv[3] . '.log';
$output_filepath = __DIR__ . DIRECTORY_SEPARATOR . 'output_' . $argv[2] .  '_' . $argv[3] . '.txt';

list($msec, $sec) = explode(" ", microtime());
$msg = 'worker ' . $argv[1] . ' started ' . date('Y/m/d H:i:s', $sec) . substr((string)$msec, 1) . PHP_EOL;
echo $msg;
file_put_contents($log_filepath, $msg, FILE_APPEND | LOCK_EX);

$hash_times = 100;
$output = '';

for ($i = 0; $i < (int)$argv[2]; $i++) {
    
    $temp = microtime() . ' ' . (string)mt_rand();
    for ($j = 0; $j < $hash_times; $j++) {
        $temp = sha1($temp);
    }
    $output .= $temp . PHP_EOL;
}

file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . $argv[1] . '.tmp', $output);

list($msec, $sec) = explode(" ", microtime());
$msg = 'worker ' . $argv[1] . ' finished ' . date('Y/m/d H:i:s', $sec) . substr((string)$msec, 1) . PHP_EOL;
echo $msg;
file_put_contents($log_filepath, $msg, FILE_APPEND | LOCK_EX);

$temp_files = glob(__DIR__ . DIRECTORY_SEPARATOR . '*.tmp');
if (is_array($temp_files) && count($temp_files) == $argv[3] && !file_exists($output_filepath)) {
    foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . '*.tmp') as $filepath) {
        $temp = file_get_contents($filepath);
        file_put_contents($output_filepath, $temp, FILE_APPEND | LOCK_EX);
    }
    
    foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . '*.tmp') as $filepath) {
        unlink($filepath);
    }
    
    list($msec, $sec) = explode(" ", microtime());
    $msg = 'all workers finished ' . date('Y/m/d H:i:s', $sec) . substr((string)$msec, 1) . PHP_EOL;
    echo $msg;
    file_put_contents($log_filepath, $msg, FILE_APPEND | LOCK_EX);
}

sleep(10);