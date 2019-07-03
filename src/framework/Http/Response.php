<?php

namespace Framework\Http;

/**
 * Class Response
 * @package Framework\Http
 */
class Response
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var int
     */
    protected $code = 200;

    /**
     * @param null $data
     * @param int  $code
     */
    function __construct($data = null, $code = 200) {
        $this->data = $data;
        $this->code = $code ?? 200;
    }

    /**
     * @return JsonResponse|string
     * @throws \Framework\Exceptions\ViewNotFoundException
     */
    public function send() {
        // Inject flash data in to view
        if ($this->data instanceof View) {
            if ($_SESSION['_flash'] && !is_null($_SESSION['_flash'])) {
                $this->data->inject($_SESSION['_flash']);
                unset($_SESSION['_flash']);
            }

            // Render the view
            return $this->data->render();
        } else {
            return (new JsonResponse($this->data, $code))->send();
        }

    }

    public function redirect($to, $data) {
        $_SESSION['_flash'] = $data ?? $this->data;
        header("Location: $to", true, $this->code);
        exit;
    }
}