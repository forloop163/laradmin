<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\Finder;

class RouteServiceProvider extends ServiceProvider
{
    protected $ignoreFiles = [
        'api.php',
        'console.php',
        'channels.php',
        'web.php',
    ];

    protected $laradminMiddlewares = ['api', 'auth:web', 'laradmin'];

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapLaradminRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }


    /**
     * Define the "laradmin" routes for the applaction
     */
    protected function mapLaradminRoutes()
    {
        $routeFileFinders = $this->loadRoutesFile(base_path('routes'));

        foreach ($routeFileFinders as $finder) {
            Route::prefix('api')
                ->middleware($this->laradminMiddlewares)
                ->namespace('\App\Http\Controllers')
                ->group($finder->getPathname());
        }
    }

    /**
     * @param $path
     * @return Finder
     */
    protected function loadRoutesFile($path)
    {
        $finder = new Finder();

        return $finder
            ->files()
            ->ignoreDotFiles(true)
            ->name('*.php')
            ->exclude($this->ignoreFiles)
            ->in($path);
    }
}
