<?php

namespace App\Commands;

use Exception;
use Framework\Command;
use Framework\Queue as Worker;

/**
 * Class Queue
 * @package App\Commands
 */
class Queue extends Command
{
    /**
     * @var Worker
     */
    protected $queue;

    /**
     * Queue constructor.
     *
     * @param array $args
     */
    public function __construct(array $args = []) {
        parent::__construct($args);
        $this->queue = new Worker();
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        if (isset($this->argument()[0])) {
            $command = $this->argument()[0];
            $this->$command();
        }
    }

    /**
     *
     */
    public function work() {
        while (true) {
            $this->info("Processing ({$this->queue->processing()}/{$this->queue->length()})");

            try {
                $this->queue->process();
            } catch (Exception $exception) {
                $this->error($exception->getMessage());
                $this->restart($exception->getTraceAsString());
            }

            sleep(5);
        }
    }

    /**
     * @param null $message
     */
    public function restart($message = null) {
        $this->error($message ?? 'Processes exit. Restarting.');
        sleep(1);
        (new self)->handle();
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->restart();
    }
}