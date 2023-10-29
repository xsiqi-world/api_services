<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;

class AdminUserController extends Controller
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
     * @return \App\Http\Controllers\JsonResponse
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
     * @return \App\Http\Controllers\JsonResponse
     */
    public function login (Request $request) {
        // 用户登录逻辑
//        $this->validate($request, [
//            'username ' => 'string',
//            'password' => 'required|min:6|max:50',
//        ]);
//
//        $user = AdminUser::where('username', $request['username'])
//            ->first();
//
//        $pwdEquality = password_verify($request['password'], $user['password']);
//
//        if (!$pwdEquality) {
//            return $this->fail('Invalid username or Password');
//        }
//
//        $request->session()->put('loginInfo', [
//            'admin_id' => $user['id'],
//            'username' => $user['username'],
//            'rules'    => [],
//        ]);
//
//        return $this->success(['csrf_token' => csrf_token()]);

        // todo 用户登录逻辑
        $this->validate($request, [
            'username ' => 'string',
            'password' => 'required|min:6|max:50',
        ]);

        // jwt token
        $credentials = $request->only('username', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->fail('Invalid username or Password');
        }
        return $this->success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    /**
     * 获取用户
     * @param Request $request
     * @return \App\Http\Controllers\JsonResponse
     */
    public function getAuthUser(Request $request)
    {
//        $user = JWTAuth::authenticate($request->token);

        $user = JWTAuth::parseToken()->authenticate();
        unset($user['password']);

        return $this->success(['user' => $user, 'access' => 'admin']);
    }

    /**
     * 刷新token
     */
    public function refresh()
    {
        return $this->responseWithToken(JWTAuth::refresh());
    }

    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        JWTAuth::logout();
    }

    /**
     * 响应
     */
    private function responseWithToken(string $token)
    {
        $response = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];

        return response()->json($response);
    }

}
