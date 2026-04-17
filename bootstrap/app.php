<?php

use App\Http\Middleware\CheckOrganizationSubscription;
use App\Http\Middleware\CurrencyContextMiddleware;
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
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn() => route('login'));
        $middleware->redirectUsersTo(fn() => route('admin.dashboard'));
        // Establish a global currency context for all web requests (display-only).
        $middleware->appendToGroup('web', CurrencyContextMiddleware::class);
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'api.client' => \App\Http\Middleware\EnsureApiClient::class,
            'not.api.client' => \App\Http\Middleware\EnsureNotApiClient::class,
            'organization.account' => EnsureOrganizationAccountActive::class,
            'organization.onboarding' => EnsureOrganizationOnboardingComplete::class,
            'organization.subscription' => CheckOrganizationSubscription::class,
            'organization.subscription.sync' => SyncOrganizationSubscriptionExpiry::class,
            'api.balance' => \App\Http\Middleware\CheckApiBalance::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
