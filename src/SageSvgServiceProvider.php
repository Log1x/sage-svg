<?php

namespace Log1x\SageSvg;

use Roots\Acorn\ServiceProvider;
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
            $this->customDirectives();
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
            'path' => $this->app->publicPath()
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
    }

    /**
     * Register custom Blade directives.
     *
     * @return void
     */
    protected function customDirectives()
    {
        if (class_exists('\BladeSvgSage\BladeSvgSage') || class_exists('\BladeSvg\SvgFactory')) {
            return;
        }

        if (($directives = collect($this->app->config->get('svg.directives')))->isEmpty()) {
            return;
        }

        $directives->each(function ($path, $directive) {
            Blade::directive($directive, function ($expression) use ($path) {
                $expression = collect(explode(',', $expression));

                $expression = $expression->put(0, sprintf(
                    "'%s.%s'",
                    $path,
                    str_replace("'", '', $expression->first())
                ))->implode(',');

                return "<?php echo e(get_svg($expression)); ?>";
            });
        });
    }
}
