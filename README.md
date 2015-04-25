# PHP Worker

Run easy background tasks with dependency injection and async requests.

```php
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

```

This package is a suggestion how to use [Event Loop from ReactPHP](https://github.com/reactphp/event-loop), and integrate with anothers awesome libs like [https://github.com/laravel/framework/tree/5.0/src/Illuminate/Container).

