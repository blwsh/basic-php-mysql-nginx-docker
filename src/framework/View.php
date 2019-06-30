<?php

namespace Framework;

use Exception;
use Framework\Util\Str;
use App\Jobs\CacheView;
use Framework\Util\HtmlMinifier;
use Framework\Exceptions\ViewNotFoundException;

/**
 * Class View
 *
 * @package Framework
 */
class View
{
    /**
     * @var int
     */
    protected static $count = 0;

    /**
     * @var View
     */
    protected static $root;

    /**
     * @var View
     */
    protected static $current;

    /**
     * @var bool
     */
    protected $isRoot;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var
     */
    protected $file;

    /**
     * @var
     */
    protected $fileContents;

    /**
     * @var
     */
    protected $renderedContents;

    /**
     * @var bool
     */
    protected $isTemplate = false;

    /**
     * @var array|object
     */
    protected $vars = [];

    /**
     * @var
     */
    protected $template;

    /**
     * @var bool
     */
    protected $useCache;

    /**
     * @var bool
     */
    protected $cached = false;

    /**
     * View constructor.
     *
     * @param              $file
     * @param array|object $vars
     * @param bool         $useCached
     */
    public function __construct($file, $vars = [], bool $useCached = true)
    {
        $this->file = $file;
        $this->vars = $vars;
        $this->path = $this->path = __DIR__ . '/../resources/views/' . Str::dot($this->file) . '.php';
        $this->useCache = $useCached;
        if (!self::$root) self::$root = $this;
        $this->isRoot = $this === View::getRoot();
        self::$current = $this;
        self::$count++;
    }

    /**
     * @return string
     *
     * @note IMPORTANT! Unless in development mode (Or you specify no cache in
     *       the constructor), there will only be a new cache hit if the vars
     *       property of this view is different. This means if data is retrieved
     *       in the view, it will not be reflected. To avoid stale views being
     *       rendered, always pass data to views and never retrieve inside a view.
     *
     *       You can trigger an artificial cache miss by passing random data to
     *       the view but this is not recommended.
     *
     * @throws ViewNotFoundException
     */
    public function render() {
        if (!isDebug() && $this->useCache && $this->isRoot && $cachedView = Cache::get(json_encode([$this->path, $this->vars]), 'framework/views')) {
            $this->cached = true;
            return $cachedView;
        } else {
            unset($cachedView);
        }

        if (is_file($this->path)) {
            extract((array) $this->vars);

            ob_start(); include $this->path; $this->renderedContents = ob_get_clean();

            if (preg_match('/\<content\ .*\>\n(.+\n*)+<\/content\>/', $this->renderedContents, $matches)) {
                if (preg_match('/\<content.+\>/', $this->renderedContents, $contentTag)) {
                    $contentTag = $contentTag[0];
                    $template = null;
                    $title = null;

                    if (preg_match('/template=\"([^"^\n]+)\"/', $contentTag, $matches)) {
                        $template = $matches[1];

                        $layoutView = view($template, $this->vars);
                        $layoutView->setIsTemplate(true);

                        if (preg_match('/<content.*>\n((?:.*\r?\n?)*)<\/content>/m', $this->renderedContents, $templateContents)) {

                            $this->renderedContents = str_replace(
                                '<yiled value="content"></yiled>',
                                $templateContents[1],
                                $layoutView->render()
                            );
                        }
                    }

                    if (preg_match('/title=\"([^"^\n]+)\"/', $contentTag, $matches)) {
                        $title  = $matches[1];
                        $this->renderedContents = str_replace('<yield value="title"></yield>', $title, $this->renderedContents);
                    }
                }
            }

            // Cache this page
            dispatch(new CacheView($this));

            return !isDebug() ? (new HtmlMinifier([]))->minify($this->renderedContents) : $this->renderedContents;
        } else {
            throw new ViewNotFoundException('Unable to find view with name ' . $this->file . ' at path ' . $this->path .')');
        }
    }

    /**
     * @param array $data
     */
    public function inject(array $data) {
        $this->vars = array_merge($this->vars, $data);
    }

    /**
     * @return int
     */
    public static function getCount(): int
    {
        return self::$count;
    }

    /**
     * @return View
     */
    public static function getRoot(): View
    {
        return self::$root;
    }

    /**
     * @return View
     */
    public static function getCurrent()
    {
        return self::$current;
    }

    /**
     * @return bool
     */
    public function isRoot(): bool
    {
        return $this->isRoot;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function getFileContents()
    {
        return $this->fileContents;
    }

    /**
     * @return mixed
     */
    public function getRenderedContents()
    {
        return $this->renderedContents;
    }

    /**
     * @return array|object
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return bool
     */
    public function isTemplate(): bool
    {
        return $this->isTemplate;
    }

    /**
     * @return bool
     */
    public function shouldCache(): bool
    {
        return $this->useCache;
    }

    /**
     * @param bool $isTemplate
     */
    public function setIsTemplate(bool $isTemplate)
    {
        $this->isTemplate = $isTemplate;
    }

    /**
     * @return Exception|false|mixed|string
     *
     * @throws ViewNotFoundException
     */
    public function __toString()
    {
        return $this->render();
    }
}