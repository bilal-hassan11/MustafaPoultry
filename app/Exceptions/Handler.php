<?php

namespace App\Exceptions;

use Dotenv\Exception\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function report(Throwable  $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }
        
        parent::report($exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable  $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            $preException = $exception->getPrevious();
            if ($preException instanceof TokenExpiredException) {
                return api_response(false, ['logout_required' => true], 'Token Expired');
            } else if ($preException instanceof TokenInvalidException) {
                return api_response(false, ['logout_required' => true], 'Token Invalid');
            } else if ($preException instanceof TokenBlacklistedException) {
                return api_response(false, ['logout_required' => true], 'Token Blacklisted');
            }
            if ($exception->getMessage() === 'Token not provided') {
                return api_response(false, ['logout_required' => true], 'Token not provided');
            } else if ($exception->getMessage() === 'User not found') {
                return api_response(false, ['logout_required' => true], 'User Not Found');
            }
        }

        if ($request->wantsJson()) {
            if ($exception instanceof ValidationException){
                return response()->json([
                    'message' => 'Validation error', 'errors' => $exception->validator->getMessageBag()
                ], 422); //type your error code.
            }
            
            if($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface){
                $code = $exception->getStatusCode() ?? 404;
            }else{
                $code = $exception->getCode() ?? 404;
            }

            $code = $code > 0 && $code < 550 ? $code : 404;

            $message = $exception->getMessage();
            if($message == ''){
                if($code == 401){
                    $message = 'You are not authorized to perform this action.';
                }elseif($code == 404){
                    $message = 'The requested resource was not found.';
                }
            }

            // return response([
            //     'error' => $message ?? 'Not Found'
            // ], 400);

            return response([
                'error' => $message ?? 'Not Found',
                'data' => [],
                // 'details' => $e,
                'status' => false,
                'msg' => $message ?? 'Not Found',
            ], 400);
            
        }

        return parent::render($request, $exception);
    }
}
