<?php
namespace Irto\Worker\Contracts;

/**
 * Contract to providers that are can be added to {@link Irto\Worker\Service}.
 * 
 */
interface WorkerProvider {

    /**
     * Register application tasks
     * 
     * @return void
     */
    public function register();
}