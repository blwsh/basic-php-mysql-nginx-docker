<?php

namespace App\Controllers;

use App\Classes\APIResponse;
use Framework\Http\Controller;
use Framework\Database\Model;
use Framework\Http\Request;
use ReflectionClass;
use ReflectionException;

/**
 * Class ApiController
 * @package App\Controllers
 */
class ApiController extends Controller
{
    /**
     * @param string $model
     *
     * @return array|object
     */
    protected function getModel(string $model) {
        $fullModelPath = '\\App\\Models\\' . $model;

        try {
            return (new ReflectionClass($fullModelPath))->newInstance();
        } catch (ReflectionException $e) {
            return ['error' => "Model ($fullModelPath) not found."];
        }
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function get(Request $request) {
        $model = $this->getModel($request->get('model'));

        if ($model instanceof Model) {
            return APIResponse::send($model::get());
        }

        return APIResponse::send([]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function find(Request $request) {
        $model = $this->getModel($request->get('model'));

        if ($model instanceof Model) {
            return APIResponse::send($model::find($request->get('id')));
        }

        return APIResponse::send([]);
    }

    /**
     * @param Request $request
     */
    public function dump(Request $request) {
        dd($request);
    }
}