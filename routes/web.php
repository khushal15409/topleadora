<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\BroadcastUsageController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\IntegrationsController;
use App\Http\Controllers\Admin\Marketing\CountryController as MarketingCountryController;
use App\Http\Controllers\Admin\Marketing\FormFieldController as MarketingFormFieldController;
use App\Http\Controllers\Admin\Marketing\LandingPageController as MarketingLandingPageController;
use App\Http\Controllers\Admin\Marketing\MarketingLeadController;
use App\Http\Controllers\Admin\Marketing\ServiceController as MarketingServiceController;
use App\Http\Controllers\Admin\OnboardingController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\OrganizationUserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RazorpayPaymentController;
use App\Http\Controllers\Admin\RevenueAnalyticsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\SubscriptionMonitoringController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\Dashboard\BroadcastController as DashboardBroadcastController;
use App\Http\Controllers\Dashboard\FollowUpController as DashboardFollowUpController;
use App\Http\Controllers\Dashboard\LeadController as DashboardLeadController;
use App\Http\Controllers\Dashboard\PipelineController as DashboardPipelineController;
use App\Http\Controllers\Dashboard\ReportsController as DashboardReportsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadCaptureController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Webhooks\RazorpayWebhookController;
use App\Http\Controllers\Webhooks\WhatsAppWebhookController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Support\Roles;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-main.xml', [SitemapController::class, 'main'])->name('sitemap.main');
Route::get('/sitemap-blog.xml', [SitemapController::class, 'blog'])->name('sitemap.blog');
Route::get('/sitemap-leads.xml', [SitemapController::class, 'leads'])->name('sitemap.leads');
Route::post('/webhooks/razorpay', RazorpayWebhookController::class)
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('webhooks.razorpay');

Route::get('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'verify'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('webhooks.whatsapp.verify');
Route::post('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'receive'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('webhooks.whatsapp.receive');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/features', [PagesController::class, 'features'])->name('features');
Route::get('/pricing', [PagesController::class, 'pricing'])->name('pricing');

Route::get('/contact', [ContactFormController::class, 'show'])->name('contact');
Route::post('/contact', [ContactFormController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

// Programmatic city URLs use the same pattern: /leads/{serviceSlug}-{citySlug} (see ProgrammaticLeadResolver).
Route::get('/leads/{slug}', [LeadCaptureController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('leads.landing');
Route::post('/leads', [LeadCaptureController::class, 'store'])
    ->middleware('throttle:15,1')
    ->name('leads.capture.store');
Route::get('/get-quote/{slug}', function (string $slug) {
    return redirect()->to('/leads/'.$slug, 301);
})->where('slug', '[a-z0-9-]+');
Route::post('/get-quote', [LeadCaptureController::class, 'store'])
    ->middleware('throttle:15,1')
    ->name('landing.lead.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:'.Roles::SUPER_ADMIN.'|'.Roles::ORGANIZATION])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {
        Route::middleware(['organization.account', 'organization.subscription.sync'])->group(function () {
            Route::middleware(['organization.onboarding'])->group(function () {
                Route::middleware(['organization.subscription'])->group(function () {
                    // Bare /dashboard had no route → 404. CRM entry plus safe redirect for bookmarks.
                    Route::get('/', function () {
                        $user = auth()->user();
                        if ($user !== null && $user->hasRole(Roles::SUPER_ADMIN)) {
                            return redirect()->route('admin.dashboard');
                        }

                        return redirect()->route('dashboard.leads.index');
                    })->name('home');

                    Route::get('/leads', [DashboardLeadController::class, 'index'])->name('leads.index');
                    Route::get('/leads/create', [DashboardLeadController::class, 'create'])->name('leads.create');
                    Route::post('/leads', [DashboardLeadController::class, 'store'])->name('leads.store');
                    Route::get('/leads/{lead}/summary', [DashboardLeadController::class, 'summary'])->name('leads.summary');
                    Route::get('/leads/{lead}/edit', [DashboardLeadController::class, 'edit'])->name('leads.edit');
                    Route::put('/leads/{lead}', [DashboardLeadController::class, 'update'])->name('leads.update');
                    Route::post('/leads/{lead}/quick', [DashboardLeadController::class, 'quick'])->name('leads.quick');

                    Route::get('/pipeline', [DashboardPipelineController::class, 'index'])->name('pipeline.index');
                    Route::patch('/leads/{lead}/stage', [DashboardPipelineController::class, 'updateStage'])->name('leads.update-stage');

                    Route::get('/followups', [DashboardFollowUpController::class, 'index'])->name('followups.index');
                    Route::post('/followups/{lead}/complete', [DashboardFollowUpController::class, 'complete'])->name('followups.complete');

                    Route::get('/broadcast', [DashboardBroadcastController::class, 'index'])->name('broadcast.index');
                    Route::post('/broadcast', [DashboardBroadcastController::class, 'store'])->name('broadcast.store');

                    Route::get('/reports', [DashboardReportsController::class, 'index'])->name('reports.index');
                });
            });
        });
    });

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::prefix('admin')
    ->middleware(['auth', 'role:'.Roles::SUPER_ADMIN.'|'.Roles::ORGANIZATION])
    ->name('admin.')
    ->group(function () {
        Route::middleware(['role:'.Roles::SUPER_ADMIN])->group(function () {
            Route::resource('organizations', OrganizationController::class)->except(['show']);

            Route::get('users', [OrganizationUserController::class, 'index'])->name('users.index');
            Route::get('users/{user}', [OrganizationUserController::class, 'show'])->name('users.show');

            Route::resource('roles', RoleController::class)->only(['index', 'store', 'update', 'destroy']);

            Route::get('broadcast-usage', [BroadcastUsageController::class, 'index'])->name('broadcast-usage.index');
            Route::get('broadcast-usage/{organization}', [BroadcastUsageController::class, 'show'])->name('broadcast-usage.show');

            Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

            Route::get('integrations', [IntegrationsController::class, 'index'])->name('integrations.index');
            Route::put('integrations', [IntegrationsController::class, 'update'])->name('integrations.update');

            Route::prefix('marketing')->name('marketing.')->group(function () {
                Route::resource('services', MarketingServiceController::class)->except(['show']);
                Route::resource('countries', MarketingCountryController::class)->except(['show']);
                Route::resource('landing-pages', MarketingLandingPageController::class)
                    ->parameters(['landing-pages' => 'landing_page'])
                    ->except(['show']);
                Route::resource('form-fields', MarketingFormFieldController::class)
                    ->parameters(['form-fields' => 'form_field'])
                    ->except(['show']);
                Route::get('leads/export', [MarketingLeadController::class, 'export'])->name('leads.export');
                Route::get('leads', [MarketingLeadController::class, 'index'])->name('leads.index');
            });

            Route::get('contacts', [ContactMessageController::class, 'index'])->name('contacts.index');
            Route::patch('contacts/{contact}/read', [ContactMessageController::class, 'markAsRead'])->name('contacts.mark-read');
            Route::delete('contacts/{contact}', [ContactMessageController::class, 'destroy'])->name('contacts.destroy');

            Route::get('subscriptions', [SubscriptionMonitoringController::class, 'index'])->name('subscriptions.index');
            Route::post('subscriptions/{organization}/extend', [SubscriptionMonitoringController::class, 'extend'])->name('subscriptions.extend');
            Route::get('subscriptions/{organization}/change-plan', [SubscriptionMonitoringController::class, 'changePlanForm'])->name('subscriptions.change-plan');
            Route::put('subscriptions/{organization}/change-plan', [SubscriptionMonitoringController::class, 'changePlan'])->name('subscriptions.change-plan.update');

            Route::get('revenue', [RevenueAnalyticsController::class, 'index'])->name('revenue.index');
        });

        Route::middleware(['organization.account', 'organization.subscription.sync'])->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

            Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
            Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

            Route::middleware(['organization.onboarding'])->group(function () {
                Route::get('dashboard/plan', [SubscriptionController::class, 'organizationPlan'])->name('organization.plan');
                Route::get('checkout/{plan}', [SubscriptionController::class, 'checkoutById'])
                    ->whereNumber('plan')
                    ->name('checkout');

                Route::get('/subscription/pricing', [SubscriptionController::class, 'pricing'])->name('subscription.pricing');
                Route::get('/subscription/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
                Route::post('/subscription/activate/{plan}', [SubscriptionController::class, 'activate'])->name('subscription.activate');

                Route::post('/subscription/razorpay/order/{plan}', [RazorpayPaymentController::class, 'createOrder'])
                    ->name('subscription.razorpay.order');
                Route::post('/subscription/razorpay/verify', [RazorpayPaymentController::class, 'verify'])
                    ->name('subscription.razorpay.verify');

                Route::middleware(['organization.subscription'])->group(function () {
                    Route::get('/dashboard', DashboardController::class)->name('dashboard');
                });
            });
        });
    });
