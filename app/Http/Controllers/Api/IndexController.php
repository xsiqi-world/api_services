<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index (Request $request) {
        $request->session()->put('loginInfo', [
            'admin_id' => 1,
            'username' => 2,
            'rules'    => [],
        ]);
        
        print_r(csrf_token());
    }

    public function testPost (Request $request) {
        print_r(session('loginInfo'));exit;
        print_r(csrf_token());
    }

		/**
     * 注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string|max:50',
            'password' => 'required|string|min:6|max:50',
        ]);

        $user = new AdminUser();
        $user->username = $request['username'];
        $user->password = password_hash($request['password'], PASSWORD_DEFAULT, ['cost' => 12]);
        $user->create_time = time();
        $user->save();

        return $this->success(['csrf_token' => csrf_token()]);
		}
		
		/**
		 * 登录
		 * @param Request $request
		 * @return void
		 */
    public function login (Request $request) {
        // 用户登录逻辑
        $this->validate($request, [
            'username ' => 'string',
            'password' => 'required|min:6|max:50',
				]);
				
				$user = AdminUser::where('username', $request['username'])
						->first();

				$password = password_verify($request['password'], $user['password']);

        if (!$password) {
            return $this->fail('Invalid username or Password');
				}
				
				$request->session()->put('loginInfo', [
						'admin_id' => $user['id'],
						'username' => $user['username'],
						'rules'    => [],
				]);

				return $this->success(['csrf_token' => csrf_token()]);
    }

}
