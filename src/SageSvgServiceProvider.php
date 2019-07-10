<?php

namespace Log1x\SageSvg;

use Roots\Acorn\ServiceProvider;
use Illuminate\Support\Facades\Blade;

use function Roots\config;
use function Roots\public_path;
use function Roots\config_path;

class SageSvgServiceProvider extends ServiceProvider
{
   /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SvgSvg::class, function () {
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
            __DIR__ . '/../config/svg.php' => config_path('svg.php')
        ]);
    }

    /**
     * Return the services config.
     *
     * @return array
     */
    protected function config()
    {
        return collect(config('svg', []))->merge([
            'path' => public_path(),
        ])->all();
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
