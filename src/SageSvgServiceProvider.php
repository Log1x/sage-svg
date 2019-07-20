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
        $this->directives();
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
}
