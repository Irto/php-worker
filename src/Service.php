<?php
namespace Irto\Worker;

use React\EventLoop\Factory as EventLoopFactory;
use Illuminate\Container\Container;
use Irto\Worker\Contracts\WorkerProvider;

class Service extends Container {

    use UsefulEventLoopFunctions;

    /**
     * Classes not booted yet
     * 
     * @var array
     */
    protected $toBoot = [];

    /**
     * Create a new Service with common dependencies
     * 
     * @param array $config
     * 
     * @return Irto\Worker\Server
     */
    public static function create(array $config)
    {
        $service = new static();
        
        $service->singleton('Irto\Worker\Service', function ($service) {
            return $service;
        });
        
        // Create main loop React\EventLoop based
        $service->singleton('React\EventLoop\LoopInterface', function ($service) {
            return EventLoopFactory::create();
        });

        return $service;
    }

    /**
     * Add new provider to service
     * 
     * @return void
     */
    public function add($class)
    {
        $this->toBoot[] = $class;
        $this->singleton($class);
    }

    /**
     * Boot service, registering providers.
     * 
     * @return void
     */
    public function boot()
    {
        foreach ($this->toBoot as $key => $class) {
            array_pull($this->toBoot, $key);
            $this->registerProvider($class);
        }
    }

    /**
     * Create and/or register a provider
     * 
     * @return void
     */
    protected function registerProvider($class)
    {
        $provider = $this[$class];

        if ($provider instanceof WorkerProvider) {
            $provider->register();
        } else {
            throw new Exception("Can't register '$class'. Provider is not instance of WorkerProvider.", 1);
        }
    }

    /**
     * Start service
     * 
     * @return void
     */
    public function run()
    {
        $this->boot();

        $this['React\EventLoop\LoopInterface']->run();
    }
}