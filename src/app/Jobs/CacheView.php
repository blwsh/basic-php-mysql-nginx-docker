<?php

namespace app\Jobs;

use Framework\Cache;
use Framework\Queueable;
use Framework\Util\HtmlMinifier;
use Framework\View;

/**
 * Class CacheView
 * @package app\Jobs
 */
class CacheView extends Queueable
{
    /**
     * @var View
     */
    private $view;

    /**
     * CacheView constructor.
     *
     * @param View $view
     */
    public function __construct(View $view) {
        $this->view = $view;
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function handle()
    {
        if ($this->view->shouldCache() && $this->view->isRoot()) {
            Cache::put(json_encode([$this->view->getPath(), $this->view->getVars()]), (new HtmlMinifier([]))->minify($this->renderedContents), 'framework/views');
        }
    }
}