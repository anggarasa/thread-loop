<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'prevent.authenticated' => \App\Http\Middleware\PreventAuthenticatedAccess::class,
        ]);

        // Trust all proxies for Vercel deployment
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configure custom error pages
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Page not found'], 404);
            }
            return response()->view('errors.404', [], 404);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            $statusCode = $e->getStatusCode();

            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], $statusCode);
            }

            // Handle specific error codes
            switch ($statusCode) {
                case 403:
                    return response()->view('errors.403', [], 403);
                case 419:
                    return response()->view('errors.419', [], 419);
                case 429:
                    return response()->view('errors.429', [], 429);
                case 500:
                    return response()->view('errors.500', [], 500);
                case 503:
                    return response()->view('errors.503', [], 503);
                default:
                    return response()->view('errors.500', [], $statusCode);
            }
        });

        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Database error occurred'], 500);
            }
            return response()->view('errors.500', [], 500);
        });

        $exceptions->render(function (\Exception $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'An error occurred'], 500);
            }
            return response()->view('errors.500', [], 500);
        });
    })->create();
