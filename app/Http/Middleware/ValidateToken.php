<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ValidateToken
{
    protected $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //路由白名单
        $route = [
            'v1/product/detail'
        ];
        $uri = $this->route->uri();//跳转路由
        $user = [];
        if (in_array($uri, $route)) {
            if ($request->headers->get('Authorization', false) && JWTAuth::parseToken()->authenticate()) {
                $user = JWTAuth::parseToken()->authenticate();
                $request->attributes->add(['userInfo' => $user]);//添加参数
            }
            return $next($request);
        }

        try {
            if (!$request->headers->get('Authorization', false)) {
                return response()->json([
                    'code' => 300400,
                    'message' => 'invalid token'
                ]);
            }

            if (!$user = JWTAuth::parseToken()->authenticate()) {  //获取到用户数据，并赋值给$user
                return response()->json([
                    'code' => 1004,
                    'message' => 'user not found'
                ], 404);
            }
            //如果想向控制器里传入用户信息，将数据添加到$request里面
            $request->attributes->add(['userInfo' => $user]);//添加参数
            return $next($request);

        } catch (TokenExpiredException $e) {
            return response()->json([
                'code' => 1003,
                'message' => 'Token expired', //token已过期
            ]);

        } catch (TokenInvalidException $e) {
            return response()->json([
                'code' => 1002,
                'message' => 'Token is invalid',  //token无效
            ]);

        } catch (JWTException $e) {
            return response()->json([
                'code' => 1001,
                'message' => 'The lack of token', //token为空
            ]);

        }

    }
}
