<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;

class GoodsController extends Controller
{
    public function index (Request $request) {
        $request->session()->put('loginInfo', [
            'admin_id' => 1,
            'username' => 2,
            'rules'    => [],
        ]);

//        print_r(csrf_token());
        $this->success();
    }

}
