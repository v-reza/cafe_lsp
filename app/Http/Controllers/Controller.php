<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Response API
     *
     * @param bool $error jika tidak error response wajib object, contoh response ["error" => false, "data" => {buat object response disini}]
     * @param object $response gunakan tipe object ketika error false
     * @param string $response gunakan tipe string ketika error true untuk message
      * @param int $status http status code response
     * @return mixed JSON array
     */
    public function resJson($error = false, $response, $status)
    {
        if ($error) {
            $objError = [
                "error" => true,
                "message" => $response
            ];

            return response($objError, $status);
        }

        $objSuccess = [
            "error" => false,
            "data" => $response
        ];

        return response($response, $status);
    }
}
