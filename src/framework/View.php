<?php

namespace Framework;

use Exception;
use Framework\Exceptions\ViewNotFoundException;

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
     * @param              $file
     * @param array|object $vars
     */
    public function __construct($file, $vars = [])
    {
        $this->file = $file;
        $this->vars = $vars;
        $this->path = $this->path = '/src/resources/views/' . dot($this->file) . '.php';
    }

    /**
     * @return string
     *
     * @throws ViewNotFoundException
     */
    public function render() {
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

            return $this->renderedContents;
        } else {
            throw new ViewNotFoundException('Unable to find view with name ' . $this->file . ' at path ' . $this->path .')');
        }

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