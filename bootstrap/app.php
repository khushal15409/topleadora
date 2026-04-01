<?php

use App\Http\Middleware\CheckOrganizationSubscription;
use App\Http\Middleware\EnsureOrganizationAccountActive;
use App\Http\Middleware\EnsureOrganizationOnboardingComplete;
use App\Http\Middleware\SyncOrganizationSubscriptionExpiry;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'organization.account' => EnsureOrganizationAccountActive::class,
            'organization.onboarding' => EnsureOrganizationOnboardingComplete::class,
            'organization.subscription' => CheckOrganizationSubscription::class,
            'organization.subscription.sync' => SyncOrganizationSubscriptionExpiry::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
