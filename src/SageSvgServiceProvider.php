<?php

namespace Log1x\SageSvg;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SageSvgServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SageSvg::class, fn () => new SageSvg($this->app->make('files')));
        $this->app->alias(SageSvg::class, 'sage-svg');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/svg.php' => config_path('svg.php'),
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/svg.php', 'svg');

        $this->registerDirective();
        $this->registerCustomDirectives();
    }

    /**
     * Register the `@svg` Blade directive.
     */
    protected function registerDirective(): void
    {
        Blade::directive('svg', fn ($expression) => "<?php echo e(get_svg({$expression})); ?>");
    }

    /**
     * Register the custom Blade directives.
     */
    protected function registerCustomDirectives(): void
    {
        if (($directives = collect(config('svg.directives', [])))->isEmpty()) {
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

                return "<?php echo e(get_svg({$expression})); ?>";
            });
        });
    }
}
