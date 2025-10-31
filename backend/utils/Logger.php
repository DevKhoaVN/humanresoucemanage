<?php
namespace utils;

class Logger {
    private static ?Logger $instance = null;
    private string $logLevel;
    private string $output;


    //default info level
    private function __construct(string $logLevel = 'INFO', string $output = 'php://stdout') {
        $this->logLevel = $logLevel;
        $this->output = $output;
    }


    public static function getInstance(): Logger {
        if (self::$instance === null) {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    public function info(string $message): void {
        $this->log('INFO', $message);
    }

    public function warn(string $message): void {
        $this->log('WARN', $message);
    }

    public function error(string $message): void {
        $this->log('ERROR', $message);
    }

    public function debug(string $message): void {
        $this->log('DEBUG', $message);
    }

    private function log(string $level, string $message): void {
        $time = date('Y-m-d H:i:s');
        $formatted = "[$time] [$level] $message" . PHP_EOL;
        error_log($formatted, 3, $this->output);
    }
}
