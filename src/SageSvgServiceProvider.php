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
            $this->consumerDirectives();
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
    }

    /**
     * Register consumer-defined directives.
     *
     * @return void
     */
    protected function consumerDirectives()
    {
        if (class_exists('\BladeSvgSage\BladeSvgSage') || class_exists('\BladeSvg\SvgFactory')) {
            return;
        }

        if(($directives = Collection::make($this->app->config->get('svg.directives')))->isEmpty()) {
            return;
        }

        $directives->each(function ($path, $directive) {
            Blade::directive($directive, function ($expression) use ($path) {
                $parts = Collection::make(explode(',', $expression));
                $file = str_replace("'", "", $parts->first());

                $parts[0] = sprintf("'%s.%s'", $path, $file);
                $expression = $parts->implode(',');

                return "<?php echo e(get_svg($expression)); ?>";
            });
        });
    }
}
