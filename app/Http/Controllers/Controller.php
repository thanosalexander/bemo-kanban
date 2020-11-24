<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string|null $message
     * @param null $data
     * @return JsonResponse
     */
    protected function getSuccessfullJsonResponse(string $message = null, $data = null): JsonResponse
    {
        return $this->getJsonResponse(Response::HTTP_OK, $message, $data);
    }


    /**
     * Return a json response that is created using given params.
     *
     * @param int $code
     * @param string|null $message Overrides status messages.
     * @param null $data Appended to json with key 'data'.
     * @param array $errors
     * @return JsonResponse
     */
    protected function getJsonResponse(int $code = 200, string $message = null, $data = null, $errors = []): JsonResponse
    {
        $response = [
            'message' => $message,
            'errors' => $errors,
            'data' => $data
        ];

        return response()->json($response, $code);
    }
}
