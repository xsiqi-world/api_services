<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('jwt.api.auth', ['except' => ['register', 'login', 'logout']]);
    // }

    public $loginAfterSignUp = true;

    /**
    * 用户注册
    */
    public function register(Request $request)
    {
        // jwt token
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string|min:6|max:50',
        ]);

        if (User::where('username', $request['username'])->first()) {
            return $this->fail('Username has been registered');
        }

        $user = new User();
        $user->username = $request['username'];
        $user->password = password_hash($request['password'], PASSWORD_DEFAULT, ['cost' => 12]);
        $user->create_time = date('Y-m-d H:i:s', time());
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return $this->success($user);
    }

    /**
    * 用户登录
    */
    public function login(Request $request)
    {
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
//        return $this->responseWithToken($token);

        return $this->success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    /**
     * 获取用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthUser(Request $request)
    {
//        $user = JWTAuth::authenticate($request->token);

        $user = JWTAuth::parseToken()->authenticate();
        unset($user['password']);

        return $this->success(['user' => $user]);
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
