<?php

namespace Framework;

use Exception;
use Framework\Exceptions\ViewNotFoundException;
use function htmlentities;
use function preg_match;
use function preg_replace;
use function str_replace;

class View
{
    protected $path;

    protected $file;

    protected $fileContents;

    protected $renderedContents;

    protected $isTemplate = false;

    protected $vars = [];

    protected $template;

    /**
     * View constructor.
     *
     * @param       $file
     * @param array $vars
     */
    public function __construct($file, $vars = [])
    {
        $this->file = $file;
        $this->vars = $vars;
    }

    /**
     * @throws ViewNotFoundException
     */
    public function render() {
        $this->path = '/src/resources/views/' . dot($this->file) . '.php';

        if (is_file($filename = '/src/resources/views/' . dot($this->file) . '.php')) {
            extract($this->vars);
            ob_start(); include $filename; $this->renderedContents = ob_get_clean();

            if (preg_match('/\<content\ .*\>\n(.+\n*)+<\/content\>/', $this->renderedContents, $matches)) {
                if (preg_match('/\<content.+\>/', $this->renderedContents, $contentTag)) {
                    $title = $template = null;
                    $contentTag = $contentTag[0];

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
            } else if (!$this->isTemplate) {
                return new Exception('Views should have a content tag as root element with attribute template.');
            }

            return $this->renderedContents;
        } else {
            throw new ViewNotFoundException('Unable to find view with name ' . $this->file . '.php (Path: ' . $this->path .')');
        }
    }

    public function identifyTemplate($file) {

    }

    /**
     * @return bool
     */
    public function isTemplate(): bool
    {
        return $this->isTemplate;
    }

    /**
     * @param bool $isTemplate
     */
    public function setIsTemplate(bool $isTemplate)
    {
        $this->isTemplate = $isTemplate;
    }


}