<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Resources\Json\Resource;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 接口请求成功
     * @param array $data
     * @return JsonResponse
     */
    public function success($data = [])
    {
        if ($data instanceof Resource && $data->resource == null) {
            $data = [];
        }

        return response()->json([
            'code' => 100000,
            'message' => __('code.100000'),
            'data' => empty($data) ? (object)$data : $data
        ]);
    }

    /**
     * 接口请求失败
     * @param string $msg
     * @param string $code
     * @return JsonResponse
     */
    public function fail($msg = '', $code = '200202')
    {
        return response()->json([
            'code' => (int)$code,
            'message' => $msg === '' ? __('code.' . $code) : $msg,
            'data' => (object)[]
        ]);
    }

}
