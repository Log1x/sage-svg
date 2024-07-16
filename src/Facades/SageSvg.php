<?php

namespace Log1x\SageSvg\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string render(string $image, string|array $class = '', array $attrs = [], array $options = []) Render the SVG as HTML.
 *
 * @see \Log1x\SageSvg\SageSvg
 */
class SageSvg extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sage-svg';
    }
}
