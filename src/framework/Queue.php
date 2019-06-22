<?php

namespace Framework;

use function array_pop;
use function dump;
use Exception;
use function file_put_contents;
use Framework\Traits\Singleton;
use function serialize;
use stdClass;
use function unserialize;

/**
 * Class Queue
 *
 * @package framework
 */
class Queue
{
    use Singleton;

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
        if ($json = file_get_contents($path = __DIR__ .'/../queue.json')) {
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
    public function process() {

        if ($json = file_get_contents($path = __DIR__ .'/../queue.json')) {
            $queued = json_decode($json);
            $count = 0;

            foreach ($queued as $key => $item) {
                unserialize($item->object)->handle();

                $count++;

                unset($queued[$key]);

                if ($count >= $this->perBatch) break;
            }

            file_put_contents($path, $queued);
        }
    }

    /**
     * @return bool|int
     */
    public function length() {
        if ($json = file_get_contents($path = '../queue.json')) {
            return count(json_decode($json));
        }

        return false;
    }

    /**
     * @return bool|int
     */
    public function processing() {
        if ($json = file_get_contents($path = '../queue.json')) {
            return count(array_filter(json_decode($json) ?? [], function($item) {
                return $item['processing'];
            }));
        }

        return false;
    }

    /**
     * @return bool|int
     */
    public function processed() {
        if ($json = file_get_contents($path = '../queue.json')) {
            return count(array_filter(json_decode($json) ?? [], function($item) {
                return $item['processed'];
            }));
        }

        return false;
    }

    /**
     *
     */
    public function nextBatch() {

    }
}