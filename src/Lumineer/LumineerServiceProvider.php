<?php

/**
 * This file is part of the Lumineer role & 
 * permission management solution for Lumen.
 *
 * @author Vince Kronlein <vince@19peaches.com>
 * @license https://github.com/19peaches/lumineer/blob/master/LICENSE
 * @copyright 19 Peaches, LLC. All Rights Reserved.
 */

namespace Peaches\Lumineer;

use Illuminate\Support\ServiceProvider;

/**
 * Lumineer service provider.
 */
class LumineerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files and models
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('lumineer.php'),
            __DIR__ . '/../models/Role.php' => app_path('Role.php'),
            __DIR__ . '/../models/Permission.php' => app_path('Permission.php'),
            __DIR__ . '/../models/migration.php' => database_path('migrations/' . date('Y_m_d_His') . "_user_setup_tables.php"),
        ]);

        // Register commands
        $this->commands([
            'command.lumineer.migration', 
            'command.vendor.publish'
        ]);
        
        
        // Register blade directives
        $this->bladeDirectives();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLumineer();

        $this->registerCommands();

        $this->mergeConfig();
    }

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
        $blade = app('view')->getEngineResolver()->resolve('blade')->getCompiler();

        // Call to Lumineer::hasRole
        $blade->directive('role', function ($expression) {
            return "<?php if (lumineer()->hasRole{$expression}) : ?>";
        });

        $blade->directive('endrole', function ($expression) {
            return "<?php endif; ?>";
        });

        // Call to Lumineer::can
        $blade->directive('permission', function ($expression) {
            return "<?php if (lumineer()->can{$expression}) : ?>";
        });

        $blade->directive('endpermission', function ($expression) {
            return "<?php endif; ?>";
        });

        // Call to Lumineer::ability
        $blade->directive('ability', function ($expression) {
            return "<?php if (lumineer()->ability{$expression}) : ?>";
        });

        $blade->directive('endability', function ($expression) {
            return "<?php endif; ?>";
        });
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerLumineer()
    {
        $this->app->bind('lumineer', function ($app) {
            return new Lumineer($app);
        });
        
        $this->app->alias('Lumineer', 'Peaches\Lumineer\Lumineer');
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->singleton('command.lumineer.migration', function ($app) {
            return new MigrationCommand();
        });

        $this->app->singleton('command.vendor.publish', function ($app) {
            return new VendorPublishCommand($app['files']);
        });
    }

    /**
     * Merges user's and Lumineer's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php',
            'lumineer'
        );
    }

    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.lumineer.migration',
            'command.vendor.publish'
        ];
    }
}
