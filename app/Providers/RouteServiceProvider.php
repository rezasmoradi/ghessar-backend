<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Twit;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->registerModelBinding();

        $this->routes(function () {
            $this->mapApiRoutes();

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    protected function mapApiRoutes()
    {
        $files = File::files(base_path('routes/api'));
        foreach ($files as $file) {
            $path = $file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename();
            $name = $file->getFilenameWithoutExtension();

            Route::prefix("api/{$name}")
                ->middleware('api')
                ->namespace($this->namespace)
                ->group($path);
        }
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    private function registerModelBinding()
    {
        Route::model('user', User::class);
        Route::model('twit', Twit::class);
        Route::model('comment', Comment::class);
    }
}
