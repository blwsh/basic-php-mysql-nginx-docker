<?php

namespace Framework\Http;

/**
 * Class JsonResponse
 * @package Framework\Http
 */
class JsonResponse extends Response
{
    /**
     * @param null $data
     * @param int  $code
     */
    function __construct($data = null, $code = 200) {
        parent::__construct($data, $code);
        $this->data = $data;
    }

    /**
     * @return false|JsonResponse|string
     */
    public function send() {
        header_remove();

        $status = [200 => '200 OK', 400 => '400 Bad Request', 422 => 'Unprocessable Entity', 500 => '500 Internal Server Error'];

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        header('Status: '. $status[$this->code]);
        header("Cache-Control: no-transform,public,max-age=0,s-maxage=0");
        header('Content-Type: application/json; charset=utf-8');

        http_response_code($this->code);

        return json_encode($this->data ?? [], isDebug() ? JSON_PRETTY_PRINT : null);
    }
}