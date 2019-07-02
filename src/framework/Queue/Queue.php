<?php

namespace Framework\Queue;

use Framework\Traits\Singleton;

/**
 * Class Queue
 *
 * @package framework
 */
class Queue
{
    use Singleton;

    /**
     * Path to the file that stores queue state.
     */
    const file = 'queue.json';

    /**
     * @var int
     */
    protected $perBatch = 50;
    /**
     * @var int
     */
    protected $timeout = 2;
    /**
     * @var array
     */
    protected $processing = [];

    /**
     * Queue constructor.
     *
     * @param int $perBatch
     * @param int $timeout
     */
    public function __construct(int $perBatch = 50, int $timeout = 2)
    {
        $this->perBatch = $perBatch;
        $this->timeout = $timeout;
    }

    /**
     * @param $object
     */
    public function dispatch(Queueable $object)
    {
        if ($json = file_get_contents($path = app()->getRoot() . '/' . self::file)) {
            $array = json_decode($json);

            array_push($array, [
                'object' => serialize($object),
                'timestamp' => time(),
                'processing' => false,
                'processed' => false
            ]);

            file_put_contents($path, json_encode($array));
        } else {
            file_put_contents($path, '[]');
            self::dispatch($object);
        }
    }

    /**
     *
     */
    public function process()
    {

        if ($json = file_get_contents($path = app()->getRoot() . '/' . self::file)) {
            $queued = json_decode($json);
            $count = 0;

            foreach ($queued as $key => $item) {
                unserialize($item->object)->handle(); unset($queued[$key]);
                if (++$count >= $this->perBatch) break;
            }

            file_put_contents($path, $queued);
        }
    }

    /**
     * @return int
     */
    public function length()
    {
        if ($json = file_get_contents($path = app()->getRoot() . '/queue.json')) {
            return count(json_decode($json));
        }

        return 0;
    }

    /**
     * @return int
     */
    public function processing()
    {
        return min($this->perBatch, $this->length());
    }

    /**
     * @return int
     */
    public function processed()
    {
        if ($json = file_get_contents($path = app()->getRoot() . '/' . self::file)) {
            return count(array_filter(json_decode($json) ?? [], function($item) {
                return $item['processed'];
            }));
        }

        return 0;
    }

    /**
     *
     */
    public function nextBatch()
    {

    }
}