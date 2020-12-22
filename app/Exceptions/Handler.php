<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        // return parent::render($request, $exception);

        return $this->customAjaxException($request, $exception);
    }


    /**
     * 自定义 ajax请求 异常返回数据格式
     *
     * @param $request
     * @param Exception $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function customAjaxException($request, Exception $exception){
        if ($exception instanceof TokenExpiredException) {
            //token已过期
            $code = 300401;
            $message = __('code.'.$code);
        } elseif ($exception instanceof UserNotDefinedException) {
            //用户不存在
            $code = 300403;
            $message = __('code.'.$code);
        } elseif ($exception instanceof TokenBlacklistedException) {
            //token黑明单
            $code = 300400;
            $message = __('code.'.$code);
        } elseif ($exception instanceof TokenInvalidException) {
            //token无效
            $code = 300400;
            $message = __('code.'.$code);
        } elseif ($exception instanceof UnauthorizedHttpException) {
            if ($exception->getPrevious() instanceof TokenExpiredException) {
                //token已过期
                $code = 300401;
            } elseif ($exception->getPrevious() instanceof TokenInvalidException) {
                //token无效
                $code = 300400;
            } elseif ($exception->getPrevious() instanceof TokenBlacklistedException) {
                //token黑明单
                $code = 300400;
            } elseif ($exception->getPrevious() instanceof UserNotDefinedException) {
                //用户不存在
                $code = 300403;
            } else {
                $code = 300406;
            }
            $message = __('code.'.$code);
        } elseif ($exception instanceof ModelNotFoundException) {
            // 方法不存在
            $code = 500404;
            $message = __('code.'.$code);
        } elseif ($exception instanceof ValidationException) {
            // 表单验证失败
            $code = 400422;
            $message = __('code.'.$code);
            $errors = $exception->errors();
            foreach ($errors as $error) {
                $message = $error[0];
                break;
            }
        } elseif ($exception instanceof AuthenticationException) {
            // 用户未认证
            $code = 400401;
            $message = __('code.'.$code);
        } elseif ($exception instanceof QueryException) {
            // sql执行失败
            $code = 500500;
            $message = __('code.'.$code);
        } elseif ($exception instanceof NotFoundHttpException) {
            // 页面不存在
            $code = 400404;
            $message = __('code.'.$code);
        } elseif ($exception instanceof HttpException) {
            $code = 400503;
            if($exception->getStatusCode() == 429){
                // 访问频繁
                $code = 400429;
            }
            $message = __('code.'.$code);
        } elseif ($exception instanceof FatalErrorException || $exception instanceof \ErrorException) {
            // 未找到请求方法 执行失败
            $code = 500503;
            $message = __('code.' . $code);
        } elseif (is_subclass_of($exception, BaseException::class)) {
            // 业务异常
            $code = $exception->getCode();
            $message = $exception->getMessage();
        } else{
            return parent::render($request, $exception);
        }
        return response()->json([
            'code'    => (int)$code,
            'message' => $message,
            'data'    => (object)[]
        ],200);
    }

}
