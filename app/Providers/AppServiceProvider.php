<?php

namespace App\Providers;

use App\Models\User;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        Gate::define('viewApiDocs', function (?User $user) {
            return true;
        });

        Scramble::resolveTagsUsing(function ($routeInfo, $operation) {
            $uri = $routeInfo->route->uri();
            if ($uri === 'api/login') {
                return ['Autenticación'];
            }
            if ($uri === 'api/user') {
                return ['User'];
            }
            if (str_contains($uri, 'prices')) {
                return ['Precios'];
            }
            if (str_contains($uri, 'products')) {
                return ['Productos'];
            }
            return ['General'];
        });

        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );

            $openApi->tags = [
                new \Dedoc\Scramble\Support\Generator\Tag('Autenticación', 'Endpoints de inicio de sesión y autenticación'),
                new \Dedoc\Scramble\Support\Generator\Tag('User', 'Información del usuario autenticado'),
                new \Dedoc\Scramble\Support\Generator\Tag('Productos', 'Gestión de productos'),
                new \Dedoc\Scramble\Support\Generator\Tag('Precios', 'Gestión de precios multimoneda'),
            ];
        });
    }
}
