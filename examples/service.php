#!/usr/bin/env php
<?php

require '../vendor/autoload.php';

class MemoryMonitor implements Irto\Worker\Contracts\WorkerProvider {

    protected $service;

    public function __construct(Irto\Worker\Service $service)
    {
        $this->service = $service;
    }
    
    public function register()
    {
        $this->service->addPeriodicTimer(10, array($this, 'showUsage'));
    }

    public function showUsage()
    {
        $memory = memory_get_usage() / 1024;
        $formatted = number_format($memory, 3).'K';
        echo date('d-m-Y H:i:s') . " - Current memory usage: {$formatted}\n";
    }
}

$service = Irto\Worker\Service::create([]);

$service->add('MemoryMonitor');

$service->run();