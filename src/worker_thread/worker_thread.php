<?php

class WorkerThread extends Thread{

    private $num;
    private $each;
    private $times;
    
    public function __construct($num, $each, $times){
        $this->num = $num;
        $this->each = $each;
        $this->times = $times;
    }
    
    public function run(){
    
        $log_filepath = __DIR__ . DIRECTORY_SEPARATOR . 'main_' . (string)$this->each .  '_' . (string)$this->times . '.log';
        $output_filepath = __DIR__ . DIRECTORY_SEPARATOR . 'output_' . (string)$this->each .  '_' . (string)$this->times . '.txt';
        
        list($msec, $sec) = explode(" ", microtime());
        $msg = 'worker ' . $this->num . ' started ' . date('Y/m/d H:i:s', $sec) . substr((string)$msec, 1) . PHP_EOL;
        echo $msg;
        file_put_contents($log_filepath, $msg, FILE_APPEND);
        
        $hash_times = 100;
        $output = '';
        
        for ($i = 0; $i < (int)$this->each; $i++) {
            $temp = microtime() . ' ' . (string)mt_rand();
            for ($j = 0; $j < $hash_times; $j++) {
                $temp = sha1($temp);
            }
            $output .= $temp . PHP_EOL;
        }
        
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . $this->num . '.tmp', $output);
        
        list($msec, $sec) = explode(" ", microtime());
        $msg = 'worker ' . $this->num . ' finished ' . date('Y/m/d H:i:s', $sec) . substr((string)$msec, 1) . PHP_EOL;
        echo $msg;
        file_put_contents($log_filepath, $msg, FILE_APPEND);
        
        $temp_files = glob(__DIR__ . DIRECTORY_SEPARATOR . '*.tmp');
        if (is_array($temp_files) && count($temp_files) == $this->times && !file_exists($output_filepath)) {
            foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . '*.tmp') as $filepath) {
                $temp = file_get_contents($filepath);
                file_put_contents($output_filepath, $temp, FILE_APPEND);
            }
            
            foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . '*.tmp') as $filepath) {
                unlink($filepath);
            }
            
            list($msec, $sec) = explode(" ", microtime());
            $msg = 'all workers finished ' . date('Y/m/d H:i:s', $sec) . substr((string)$msec, 1) . PHP_EOL;
            echo $msg;
            file_put_contents($log_filepath, $msg, FILE_APPEND);
        }
        
    }
}
