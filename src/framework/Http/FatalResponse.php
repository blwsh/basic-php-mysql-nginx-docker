<?php

namespace Framework\Http;

/**
 * Class FatalResponse
 * @package Framework\Http
 */
class FatalResponse extends Response
{
    /**
     * @var int
     */
    protected $code = 500;

    /**
     * @param null $data
     * @param int  $code
     */
    public function __construct($data = null, $code = 500) {
        parent::__construct();
        $this->code = $code;
    }

    /**
     * @return JsonResponse|void
     */
    public function send() {
        http_response_code($this->code);
        echo view('pages.500');
        exit;
    }
}