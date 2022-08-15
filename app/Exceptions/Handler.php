<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            msg('Session Expired',3);
            if (auth('web')->check()){
                if ($request->ajax()){
                    return apiResponse(403,\trans('general.session_expired'));
                }
                return redirect()->route('company.dashboard');
            }
            return redirect('/');
        }

        return parent::render($request, $exception);
    }

    public function unauthenticated($request, AuthenticationException $exception)
    {

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        } else {
            $guard = array_get($exception->guards(), 0);
            \Session::put('redirect',$request->fullUrl());
            switch ($guard) {
                case 'admin':
                    $login = 'back-office/login';
                    break;
                default:
                    $login = 'agent/login';
                    break;
            }

            return redirect($login);
        }
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'message' => __('validation.invalid_data'),
            'errors' => $exception->errors(),
        ], $exception->status);
    }
}
