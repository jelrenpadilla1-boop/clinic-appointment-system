<?php
// bootstrap/app.php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
        
        // Add middleware to groups if needed
        $middleware->web(append: [
            // Add custom web middleware here
        ]);
        
        // Configure middleware for specific routes
        $middleware->redirectUsersTo(function ($request) {
            $user = $request->user();
            
            if ($user) {
                switch ($user->role) {
                    case 'admin':
                        return route('admin.dashboard');
                    case 'doctor':
                        return route('doctor.dashboard');
                    case 'patient':
                        return route('patient.dashboard');
                }
            }
            
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();