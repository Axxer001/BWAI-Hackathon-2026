<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Only apply ForceJsonResponse to API routes, not web routes
        $middleware->api([\App\Http\Middleware\ForceJsonResponse::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Resource not found.'
            ], 404);
        });


        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.'
            ], 401);
        });


        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {

            if ($e->getPrevious() instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Resource not found.'
                ], 404);
            }

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?: 'The requested endpoint does not exist.'
            ], 404);
        });
    })->create();

