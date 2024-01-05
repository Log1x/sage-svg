<?php

/**
 * Sage SVG Helpers
 */

use Log1x\SageSvg\SageSvg;

use function Roots\app;

/**
 * Return the inlined contents of an SVG if it exists.
 *
 * @param  string       $image
 * @param  string|array $class
 * @param  array        $attrs
 * @param  array        $options
 * @return string
 */

if (! function_exists('get_svg')) {
    function get_svg($image, $class = '', $attrs = [], $options = [])
    {
        return app(SageSvg::class)->render($image, $class, $attrs, $options);
    }
}
