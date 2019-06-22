<?php

namespace Framework\Util;

/**
 * Class HtmlMinifier
 *
 * @author https://github.com/jenstornell/tiny-html-minifier
 *
 * @note Thanks to jenstornell for this class.
 *
 * @package Framework\Util
 */
class HtmlMinifier {
    /**
     * @var
     */
    private $options;

    /**
     * @var string
     */
    private $output = '';

    /**
     * @var array
     */
    private $build = [];

    /**
     * @var int
     */
    private $skip = 0;

    /**
     * @var string
     */
    private $skipName = '';

    /**
     * @var bool
     */
    private $head = false;

    /**
     * @var array
     */
    private $elements = [
        'skip' => [
            'code',
            'pre',
            'script',
            'textarea',
        ],
        'inline' => [
            'a',
            'abbr',
            'acronym',
            'b',
            'bdo',
            'big',
            'br',
            'cite',
            'code',
            'dfn',
            'em',
            'i',
            'img',
            'kbd',
            'map',
            'object',
            'samp',
            'small',
            'span',
            'strong',
            'sub',
            'sup',
            'tt',
            'var',
            'q',
        ],
        'hard' => [
            '!doctype',
            'body',
            'html',
        ]
    ];

    /**
     * HtmlMinifier constructor.
     *
     * @param $options
     */
    function __construct($options) {
        $this->options = $options;
    }

    /**
     * @param $html
     *
     * @return string
     */
    function minify($html) {
        if(!isset($this->options['disable_comments']) || !$this->options['disable_comments'] ) {
            $html = $this->removeComments($html);
        }

        $rest = $html;
        while(!empty($rest)) :

            $parts = explode('<', $rest, 2);
            $this->walk($parts[0]);
            $rest = (isset($parts[1])) ? $parts[1] : '';
        endwhile;
        return $this->output;
    }

    /**
     * @param $part
     */
    function walk(&$part) {
        $tag_parts = explode('>', $part);
        $tag_content = $tag_parts[0];

        if(!empty($tag_content)) {
            $name = $this->findName($tag_content);
            $element = $this->toElement($tag_content, $part, $name);
            $type = $this->toType($element);
            if($name == 'head') {
                $this->head = ($type == 'open') ? true : false;
            }
            $this->build[] = [
                'name' => $name,
                'content' => $element,
                'type' => $type
            ];
            $this->setSkip($name, $type);
            if(!empty($tag_content)) {
                $content = (isset($tag_parts[1])) ? $tag_parts[1] : '';
                if($content !== '') {
                    $this->build[] = [
                        'content' => $this->compact($content, $name, $element),
                        'type' => 'content'
                    ];
                }
            }
            $this->buildHtml();
        }
    }

    /**
     * @param string $content
     *
     * @return string|string[]|null
     */
    function removeComments($content = '') {
        return preg_replace('/(?=<!--)([\s\S]*?)-->/', '', $content);
    }

    /**
     * @param $needle
     * @param $haystack
     *
     * @return bool
     */
    function contains($needle, $haystack) {
        return strpos($haystack, $needle) !== false;
    }

    /**
     * @param $element
     *
     * @return string
     */
    function toType($element) {
        $type = (substr($element, 1, 1) == '/') ? 'close' : 'open';
        return $type;
    }

    /**
     * @param $element
     * @param $noll
     * @param $name
     *
     * @return mixed|string
     */
    function toElement($element, $noll, $name) {
        $element = $this->stripWhitespace($element);
        $element = $this->addChevrons($element, $noll);
        $element = $this->removeSelfSlash($element);
        $element = $this->removeMeta($element, $name);
        return $element;
    }

    /**
     * @param $element
     * @param $name
     *
     * @return mixed
     */
    function removeMeta($element, $name) {
        if($name == 'style') {
            $element = str_replace([
                ' type="text/css"',
                "' type='text/css'"
            ],
                ['', ''], $element);
        } elseif($name == 'script') {
            $element = str_replace([
                ' type="text/javascript"',
                " type='text/javascript'"
            ],
                ['', ''], $element);
        }
        return $element;
    }

    /**
     * @param $element
     *
     * @return string
     */
    function stripWhitespace($element) {
        if($this->skip == 0) {
            $element = preg_replace('/\s+/', ' ', $element);
        }
        return trim($element);
    }

    /**
     * @param $element
     * @param $noll
     *
     * @return string
     */
    function addChevrons($element, $noll) {
        if (empty($element)) {
            return $element;
        }
        $char = ($this->contains('>', $noll)) ? '>' : '';
        $element = '<' . $element . $char;
        return $element;
    }

    /**
     * @param $element
     *
     * @return string
     */
    function removeSelfSlash($element) {
        if(substr($element, -3) == ' />') {
            $element = substr($element, 0, -3) . '>';
        }
        return $element;
    }

    /**
     * @param $content
     * @param $name
     * @param $element
     *
     * @return string|string[]|null
     */
    function compact($content, $name, $element) {
        if($this->skip != 0) {
            $name = $this->skipName;
        } else {
            $content = preg_replace('/\s+/', ' ', $content);
        }
        if(in_array($name, $this->elements['skip'])) {
            return $content;
        } elseif(
            in_array($name, $this->elements['hard']) ||
            $this->head
        ) {
            return $this->minifyHard($content);
        } else {
            return $this->minifyKeepSpaces($content);
        }
    }

    /**
     *
     */
    function buildHtml() {
        foreach($this->build as $build) {
            if(!empty($this->options['collapse_whitespace'])) {

                if(strlen(trim($build['content'])) == 0)
                    continue;

                elseif($build['type'] != 'content' && !in_array($build['name'], $this->elements['inline']))
                    trim($build['content']);

            }
            $this->output .= $build['content'];
        }
        $this->build = [];
    }

    /**
     * @param $part
     *
     * @return string|string[]|null
     */
    function findName($part) {
        $name_cut = explode(" ", $part, 2)[0];
        $name_cut = explode(">", $name_cut, 2)[0];
        $name_cut = explode("\n", $name_cut, 2)[0];
        $name_cut = preg_replace('/\s+/', '', $name_cut);
        $name_cut = strtolower(str_replace('/', '', $name_cut));
        return $name_cut;
    }

    /**
     * @param $name
     * @param $type
     */
    function setSkip($name, $type) {
        foreach($this->elements['skip'] as $element) {
            if($element == $name && $this->skip == 0) {
                $this->skipName = $name;
            }
        }
        if(in_array($name, $this->elements['skip'])) {
            if($type == 'open') {
                $this->skip++;
            }
            if($type == 'close') {
                $this->skip--;
            }
        }
    }

    /**
     * @param $element
     *
     * @return string
     */
    function minifyHard($element) {
        $element = preg_replace('!\s+!', ' ', $element);
        $element = trim($element);
        return trim($element);
    }

    /**
     * @param $element
     *
     * @return string|string[]|null
     */
    function minifyKeepSpaces($element) {
        return preg_replace('!\s+!', ' ', $element);
    }
}

