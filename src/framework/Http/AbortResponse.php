<?php

namespace Framework\Http;

/**
 * Class AbortResponse
 * @package Framework\Http
 */
class AbortResponse extends Response
{
    /**
     * @var int
     */
    protected $code = 404;

    /**
     * @param null $data
     * @param int  $code
     */
    function __construct($data = null, $code = 404) {
        parent::__construct();
        $this->code = $code;
    }

    /**
     * @return void
     */
    public function send()
    {
        http_response_code($this->code);
        echo view('pages.404');
        exit;
    }
}