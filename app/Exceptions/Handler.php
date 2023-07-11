<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request, Throwable $th)
    {
        Log::error($th->getMessage());
        if ($th instanceof \Illuminate\Auth\AuthenticationException) {
            return response()->json([
                'status'=>'error',
                'message' => "Access denied. Please login and try again.",
            ], 401);
        }else{
            return response()->json([
                'status'=>'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
