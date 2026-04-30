<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception): Response
    {
        $response = parent::render($request, $exception);

        if (
            ! app()->environment('local')
            && in_array($response->getStatusCode(), [404, 500, 503, 403, 419])
            && ! $request->expectsJson()
            && ! str_starts_with($request->path(), 'api/')
        ) {
            $component = match ($response->getStatusCode()) {
                404 => 'Errors/Error404',
                503 => 'Maintenance',
                default => 'Errors/Error404',
            };

            return Inertia::render($component, [
                'status' => $response->getStatusCode(),
            ])
                ->toResponse($request)
                ->setStatusCode($response->getStatusCode());
        }

        return $response;
    }

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

}
