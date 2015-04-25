<?php
namespace Irto\Worker;

/**
 * Useful function from event loop with working with dependecy injector
 * 
 * @see React\EventLoop\LoopInterface
 */
trait UsefulEventLoopFunctions {

    /**
     * Enqueue a callback to be invoked repeatedly after the given interval.
     * 
     * @param numeric $interval The number of seconds to wait before execution.
     * @param callable $callback The callback to invoke.
     * 
     * @return React\EventLoop\Timer\TimerInterface
     */
    public function addPeriodicTimer($interval, $callback)
    {
        return $this['React\EventLoop\LoopInterface']->addPeriodicTimer($interval, function () use ($callback) {
            $this->call($callback);
        });
    }
}