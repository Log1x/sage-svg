<?php

namespace Log1x\SageSvg;

use Roots\Acorn\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class SageSvgServiceProvider extends ServiceProvider
{
   /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SageSvg::class, function () {
            return new SageSvg($this->config());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->bound('blade.compiler')) {
            $this->directives();
        }

        $this->publishes([
            __DIR__ . '/../config/svg.php' => $this->app->configPath('svg.php')
        ]);
    }

    /**
     * Return the services config.
     *
     * @return array
     */
    protected function config()
    {
        return collect([
            'path' => $this->app->basePath('dist')
        ])
        ->merge($this->app->config->get('svg', []))
        ->all();
    }

    /**
     * Register Blade directives.
     *
     * @return void
     */
    protected function directives()
    {
        if (class_exists('\BladeSvgSage\BladeSvgSage') || class_exists('\BladeSvg\SvgFactory')) {
            return;
        }

        Blade::directive('svg', function ($expression) {
            return "<?php echo e(get_svg($expression)); ?>";
        });

        if (! $directives = $this->config()['directives']) {
            return;
        }

        Collection::make($directives)->each(function ($path, $directive) {
            Blade::directive($directive, function ($expression) use ($path) {
                $parts = Collection::make(explode(',', $expression))->toArray();
                $parts[0] = printf("'%s.%s'", $path, str_replace("'", "", $parts[0]));
                $expression = Collection::make($parts)->implode(',');

                return "<?php echo e(get_svg($expression)); ?>";
            });
        });
    }
}
