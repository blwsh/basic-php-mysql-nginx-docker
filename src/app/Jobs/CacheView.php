<?php

namespace App\Jobs;

use Framework\Http\View;
use Framework\Queue\Queueable;
use Framework\Util\HtmlMinifier;

/**
 * Class CacheView
 * @package App\Jobs
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
            cache()::put(json_encode([$this->view->getPath(), $this->view->getVars()]), (new HtmlMinifier([]))->minify($this->view->getRenderedContents()), ['dir' => 'framework/views']);
        }
    }
}