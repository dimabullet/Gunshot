<?php

namespace BulletDigitalSolutions\Gunshot;

use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\AddEventBindingsToServiceProvider;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\AddFacadeToConfig;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\AddFilterBindingsToServiceProvider;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\AddRepositoryBindingsToServiceProvider;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\AddViewFolderToConfig;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeContract;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeController;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeEvent;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeFacade;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeFilter;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeJob;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeListener;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeModule;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeNotification;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeRepository;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeRequest;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeTransformer;
use BulletDigitalSolutions\Gunshot\Console\Commands\ModuleMaker\MakeValueObject;
use Illuminate\Support\ServiceProvider;

class GunshotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'gunshot');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'gunshot');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('gunshot.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/gunshot'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/gunshot'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/gunshot'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerCommands();

        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'gunshot');

        // Register the main class to use with the facade
        $this->app->singleton('gunshot', function () {
            return new Gunshot;
        });
    }

    /**
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            AddEventBindingsToServiceProvider::class,
            AddFacadeToConfig::class,
            AddFilterBindingsToServiceProvider::class,
            AddRepositoryBindingsToServiceProvider::class,
            AddViewFolderToConfig::class,
            MakeContract::class,
            MakeController::class,
            MakeEvent::class,
            MakeFacade::class,
            MakeFilter::class,
            MakeJob::class,
            MakeListener::class,
            MakeModule::class,
            MakeNotification::class,
            MakeRepository::class,
            MakeRequest::class,
            MakeTransformer::class,
            MakeValueObject::class,
        ]);
    }
}
